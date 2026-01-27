<?php

namespace Element\Service\Competence;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceDiscipline;
use Element\Entity\Db\CompetenceReferentiel;
use Element\Entity\Db\CompetenceSynonyme;
use Element\Entity\Db\CompetenceTheme;
use Element\Entity\Db\CompetenceType;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineServiceAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use Laminas\Mvc\Controller\AbstractActionController;
use Referentiel\Entity\Db\Referentiel;
use RuntimeException;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class CompetenceService
{
    use ProvidesObjectManager;
    use CompetenceDisciplineServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use ParametreServiceAwareTrait;

    /** COMPETENCES : ENTITY ******************************************************************************************/

    public function create(Competence $competence): Competence
    {
        $this->getObjectManager()->persist($competence);
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    public function update(Competence $competence): Competence
    {
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    public function historise(Competence $competence): Competence
    {
        $competence->historiser();
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    public function restore(Competence $competence): Competence
    {
        $competence->dehistoriser();
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    public function delete(Competence $competence): Competence
    {
        $this->getObjectManager()->remove($competence);
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    /** COMPETENCES : REQUETAGE ***************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Competence::class)->createQueryBuilder('competence')
            ->addSelect('type')->leftJoin('competence.type', 'type')
            ->addSelect('theme')->leftJoin('competence.theme', 'theme')
            ->addSelect('discipline')->leftJoin('competence.discipline', 'discipline')
            ->addSelect('synonyme')->leftJoin('competence.synonymes', 'synonyme')
            ->addSelect('referentiel')->leftJoin('competence.referentiel', 'referentiel')
        ;
        return $qb;
    }

    /** Competence[] */
    public function getCompetences(bool $withHisto = false, string $champ = 'libelle', string $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('competence.' . $champ, $order);
        if (!$withHisto) $qb = $qb->andWhere('competence.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCompetencesByType(?CompetenceType $type = null, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if ($type !== null) $qb = $qb->where('competence.type = :type')->setParameter('type', $type);
        if (!$withHisto) $qb = $qb->andWhere('competence.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Competence[] */
    public function getCompetencesWithFiltre(array $params): array
    {
        $qb = $this->createQueryBuilder();
        if (isset($params['type']) and $params['type'] !== "") {
            $qb = $qb->andWhere("type.id = :type")->setParameter('type', $params['type']);
        }
        if (isset($params['theme']) and $params['theme'] !== "") {
            $qb = $qb->andWhere("theme.id = :theme")->setParameter('theme', $params['theme']);
        }
        if (isset($params['discipline']) and $params['discipline'] !== "") {
            $qb = $qb->andWhere("discipline.id = :discipline")->setParameter('discipline', $params['discipline']);
        }
        if (isset($params['referentiel']) and $params['referentiel'] !== "") {
            $qb = $qb->andWhere("referentiel.id = :referentiel")->setParameter('referentiel', $params['referentiel']);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCompetencesByTypes(bool $withHisto = false): array
    {
        $competences = $this->getCompetences($withHisto);

        $array = [];
        foreach ($competences as $competence) {
            $libelle = $competence->getType() ? $competence->getType()->getLibelle() : "Sans type";
            $array[$libelle][] = $competence;
        }
        return $array;
    }

    public function getCompetence(?int $id): ?Competence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException("Plusieurs Competence partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCompetence(AbstractActionController $controller, string $paramName = 'competence'): ?Competence
    {
        $id = $controller->params()->fromRoute($paramName);
        $competence = $this->getCompetence($id);
        return $competence;
    }

    public function getCompetencesAsGroupOptions(?CompetenceType $type = null): array
    {
        if ($type === null) {
            $competences = $this->getCompetences();
            $competences = array_filter($competences, function (Competence $competence) {
                return $competence->getType() and $competence->getType()->getCode() !== CompetenceType::CODE_SPECIFIQUE;
            });
        } else {
            $competences = $this->getCompetencesByType($type);
        }

        if ($type === null or $type->getCode() !== CompetenceType::CODE_SPECIFIQUE) {
            $dictionnaire = [];
            foreach ($competences as $competence) {
                $libelle = ($competence->getTheme()) ? $competence->getTheme()->getLibelle() : "Sans Thèmes";
                $dictionnaire[$libelle][] = $competence;
            }
            ksort($dictionnaire);

            $options = [];
            foreach ($dictionnaire as $clef => $listing) {
                $optionsoptions = [];
                usort($listing, function (Competence $a, Competence $b) {
                    return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $a->getLibelle()) <=> iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $b->getLibelle());
                });

                foreach ($listing as $competence) {
                    $optionsoptions[$competence->getId()] = $this->competenceOptionify($competence);
                }

                if (!empty($optionsoptions)) {
                    $options[] = [
                        'label' => $clef,
                        'options' => $optionsoptions,
                    ];
                }
            }
        } else {
            $dictionnaire = [];
            foreach ($competences as $competence) {
                $libelle = ($competence->getDiscipline()) ? $competence->getDiscipline()->getLibelle() : "Sans Discipline";
                $dictionnaire[$libelle][] = $competence;
            }
            ksort($dictionnaire);

            $options = [];
            foreach ($dictionnaire as $clef => $listing) {
                $optionsoptions = [];
                usort($listing, function (Competence $a, Competence $b) {
                    return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $a->getLibelle()) <=> iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $b->getLibelle());
                });

                foreach ($listing as $competence) {
                    $optionsoptions[$competence->getId()] = $this->competenceOptionify($competence);
                }
                if (!empty($optionsoptions)) {
                    $options[] = [
                        'label' => $clef,
                        'options' => $optionsoptions,
                    ];
                }
            }
        }
        return $options;
    }

    private function competenceOptionify(Competence $competence): array
    {
        $type = $competence->getType();
        $texte = $competence->getLibelle();
        $this_option = [
            'value' => $competence->getId(),
            'attributes' => [
                'data-content' =>
                    "<span class='libelle_competence competence' title='" . ($competence->getDescription() ?? "Aucune description") . "' class='badge btn-danger'>"
                        . $texte
                        . "&nbsp;" . "<span class='badge'>"
                        . (($type !== null) ? $type->getLibelle() : "Sans type")
                        . "</span>&nbsp;"
                        . $competence->printReference()
                    . "<span class='description' style='display: none' onmouseenter='alert(event.target);'>" . ($competence->getDescription() ?? "Aucune description") . "</span>"
                    . "</span>",
            ],
            'label' => $texte,
        ];
        return $this_option;
    }

    public function getCompetenceByIdSource(string $source, string $id): ?Competence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.source = :source')
            ->setParameter('source', $source)
            ->andWhere('competence.idSource = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs compétences partagent le même id source [" . $source . "-" . $id . "]", 0, $e);
        }
        return $result;
    }

    /** @return Competence[] */
    public function getCompetencesByRefentiel(?Referentiel $referentiel, ?CompetenceType $type = null): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.referentiel = :referentiel')
            ->setParameter('referentiel', $referentiel);

        if ($type !== null) {
            $qb = $qb->andWhere('competence.type = :type')->setParameter('type', $type);
        }

        $result = $qb->getQuery()->getResult();

        /** @var Competence[] $result */
        $competences = [];
        foreach ($result as $item) {
            $competences[$item->getReference()] = $item;
        }
        return $competences;
    }

    public function getCompetenceByRefentiel(Referentiel $referentiel, string $id): ?Competence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.referentiel = :referentiel')
            ->setParameter('referentiel', $referentiel)
            ->andWhere('competence.reference = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs compétences partagent le même id referentiel [" . $referentiel->getId() . "-" . $id . "]", 0, $e);
        }
        return $result;
    }

    /** @return Competence[] */
    public function getCompetencesByTerm(string $term): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('LOWER(competence.libelle) like :search')
            ->setParameter('search', '%' . strtolower($term) . '%');
        $result = $qb->getQuery()->getResult();

        $competences = [];
        /** @var Competence $item */
        foreach ($result as $item) {
            $competences[$item->getId()] = $item;
        }
        return $competences;
    }

    /** FACADE ****************************************************************************************************/

    public function createWith(string $libelle, ?string $description, ?CompetenceType $type, ?CompetenceTheme $theme, Referentiel $referentiel, string $id, bool $persist = true): Competence
    {
        if ($id === -1) {
            $id = $this->getCompetenceMaxIdByRefentiel($referentiel) + 1;
        }

        $competence = new Competence();
        $competence->setLibelle($libelle);
        $competence->setDescription($description);
        $competence->setType($type);
        $competence->setTheme($theme);
        $competence->setReferentiel($referentiel);
        $competence->setReference($id);
        if ($persist) $this->create($competence);
        return $competence;
    }

    public function updateWith(Competence $competence, string $libelle, ?string $description, ?CompetenceType $type, ?CompetenceTheme $theme): Competence
    {
        $wasModified = false;
        if ($competence->getLibelle() !== $libelle) {
            $competence->setLibelle($libelle);
            $wasModified = true;
        }
        if ($competence->getDescription() !== $description) {
            $competence->setDescription($description);
            $wasModified = true;
        }
        if ($competence->getType() !== $type) {
            $competence->setType($type);
            $wasModified = true;
        }
        if ($competence->getTheme() !== $theme) {
            $competence->setTheme($theme);
            $wasModified = true;
        }

        if ($wasModified) $this->update($competence);
        return $competence;
    }

    public function getCompetenceByRefentielAndLibelle(?CompetenceReferentiel $referentiel, ?string $libelle, ?CompetenceType $type = null): ?Competence
    {
        if ($referentiel === null) return null;
        if ($libelle === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
            ->andWhere('competence.libelle = :libelle')->setParameter('libelle', $libelle)
            ->andWhere('competence.histoDestruction IS NULL');
        if ($type !== null) {
            $qb->andWhere('competence.type = :type')->setParameter('type', $type);
        }
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Competence::class . "] partagent le même référentiel [" . $referentiel->getLibelleCourt() . "] et le même libellé [" . $libelle . "]", 0, $e);
        }

        if ($result === null) {
            $qb = $this->createQueryBuilder()
                ->join('competence.synonymes', 'synonyme')
                ->andWhere('competence.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
                ->andWhere('synonyme.libelle = :libelle')->setParameter('libelle', $libelle)
                ->andWhere('competence.histoDestruction IS NULL');
            try {
                $result = $qb->getQuery()->getOneOrNullResult();
            } catch (NonUniqueResultException $e) {
                throw new RuntimeException("Plusieurs [" . Competence::class . "] partagent le même référentiel [" . $referentiel->getLibelleCourt() . "] et le même libellé [" . $libelle . "]", 0, $e);
            }
        }
        return $result;
    }

    public function getCompetenceByRefentielAndId(?CompetenceReferentiel $referentiel, ?string $id): ?Competence
    {
        if ($referentiel === null) return null;
        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
            ->andWhere('competence.idSource = :id')->setParameter('id', $id)
            ->andWhere('competence.histoDestruction IS NULL');
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Competence::class . "] partagent le même référentiel [" . $referentiel->getLibelleCourt() . "] et le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    private function getCompetenceMaxIdByRefentiel(Referentiel $referentiel)
    {
        $competences = $this->getCompetencesByRefentiel($referentiel);
        $max = 0;
        foreach ($competences as $competence) {
            $max = max($max, $competence->getId());
        }
        return $max;
    }


    /**
     * @param Competence[] $competences
     * @return array
     */
    public function formatCompetencesJSON(array $competences): array
    {
        $result = [];
        foreach ($competences as $competence) {
            $referentiel = $competence->getReferentiel();
            $result[] = array(
                'id' => $competence->getId(),
                'label' => $competence->getLibelle(),
                'extra' => ($referentiel === null) ? "<span class='badge' style='background-color: slategray;'>Aucun référentiel</span>" : $competence->printReference(),
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }


    /** FACADE ********************************************************************************************************/

    //Note
    // L'import des fiches métiers est "lent" car on fait beaucoup d'appel à la méthode getCompetenceByReferentielAndLibelle
    // Création d'une méthode pour générer un dictionnaire de compétences [libelle => Compétence]
    // Quid de la consommation mémoire qui pourrait être bottleneck

    /** @return array<string, Competence> */
    public function generateDictionnaire(Referentiel $referentiel, string $id, ?CompetenceType $type = null): array
    {
        $dictionnaire = [];

        $competences = $this->getCompetencesByRefentiel($referentiel, $type);
        foreach ($competences as $competence) {
            if ($id === 'libelle') $dictionnaire[$competence->getLibelle()] = $competence;
            if ($id === 'id') $dictionnaire[$competence->getId()] = $competence;
            if ($id === 'reference') $dictionnaire[$competence->getReference()] = $competence;
            foreach ($competence->getSynonymes() as $synonyme) {
                if ($id === 'libelle') $dictionnaire[$synonyme->getLibelle()] = $synonyme->getCompetence();
            }
        }
        return $dictionnaire;
    }


    public function import(string $filepath, Referentiel $referentiel, string $mode, array &$info, array &$warning, array &$error): array
    {
        $SEP_SYNONYME = '|';

        $displayCodeFonction = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION);
        // Note : ceci est un dictionnaire pour rattraper les variations dans les écritures des types.
        // Attention : il ne faut pas oublier de mettre le libellé lu du CSV en minuscules.
        $dictionnairesTypes = [
            'connaissances' => 'CONN',
            'connaissance' => 'CONN',
            'compétences opérationnelles' => 'OPER',
            'compétence opérationnelle' => 'OPER',
            'compétences comportementales' => 'COMP',
            'compétence comportementale' => 'COMP',
            'compétences socles' => 'SOCL',
            'compétence socle' => 'SOCL',
            'compétences spécifiques' => 'SPEC',
            'compétence spécifique' => 'SPEC',
        ];

        $handle = fopen($filepath, "r");

        /** Fetching the header ***************************************************************************************/
        $header = fgetcsv($handle, null, ";");
        // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
        $header[0] = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header[0]);

        // Note il y a une typo dans le csv de Referens3 + colonnes renommées par nous ...
        $positionId = array_search(Competence::COMPETENCE_HEADER_ID, $header);
        if ($positionId === false) {
            $positionId = array_search("Id_compérence", $header);
        }
        $positionLibelle = array_search(Competence::COMPETENCE_HEADER_LIBELLE, $header);
        $positionType = array_search(Competence::COMPETENCE_HEADER_TYPE, $header);
        if ($positionType === false) {
            $positionType = array_search("Registre", $header);
        }
        $positionTheme = array_search(Competence::COMPETENCE_HEADER_THEME, $header);
        if ($positionTheme === false) {
            $positionTheme = array_search("Domaine", $header);
        }
        $positionDiscipline = array_search(Competence::COMPETENCE_HEADER_DISCIPLINE, $header);
        $positionDefinition = array_search(Competence::COMPETENCE_HEADER_DEFINITION, $header);
        $positionSynonyme = array_search(Competence::COMPETENCE_HEADER_SYNONYMES, $header);

        $positionCodesEmploiType = array_search(Competence::COMPETENCE_HEADER_CODES_EMPLOI_TYPE, $header);
        if ($positionCodesEmploiType === false) {
            $positionCodesEmploiType = array_search("id_Emplois_types_RéFérens", $header);
        }
        $positionCodesFonction = array_search(Competence::COMPETENCE_HEADER_CODES_FONCTION, $header);

        $positions = ['id' => $positionId, 'libelle' => $positionLibelle, 'theme' => $positionTheme, 'type' => $positionType, 'discipline' => $positionDiscipline, 'definition' => $positionDefinition, 'emploi_type' => $positionCodesEmploiType];

        if ($positionId === false) $error[] = "La colonne <code>".Competence::COMPETENCE_HEADER_ID."</code> obligatoire est manquante !";
        if ($positionLibelle === false) $error[] = "La colonne <code>".Competence::COMPETENCE_HEADER_LIBELLE."</code> obligatoire est manquante !";
        if ($positionType === false) $error[] = "La colonne <code>".Competence::COMPETENCE_HEADER_TYPE."</code> obligatoire est manquante !";
        if ($positionTheme === false) $warning[] = "La colonne <code>".Competence::COMPETENCE_HEADER_THEME."</code> facultative est manquante.";
        if ($positionDefinition === false) $warning[] = "La colonne <code>".Competence::COMPETENCE_HEADER_DEFINITION."</code> facultative est manquante.";
        if ($positionDiscipline === false) $warning[] = "La colonne <code>".Competence::COMPETENCE_HEADER_DISCIPLINE."</code> facultative est manquante.";
        if ($positionSynonyme === false) $warning[] = "La colonne <code>".Competence::COMPETENCE_HEADER_SYNONYMES."</code> facultative est manquante.";
        if ($positionCodesEmploiType === false) $warning[] = "La colonne <code>".Competence::COMPETENCE_HEADER_CODES_EMPLOI_TYPE."</code> facultative est manquante.";
        if ($displayCodeFonction AND $positionCodesFonction === false) $warning[] = "La colonne <code>".Competence::COMPETENCE_HEADER_CODES_FONCTION."</code> facultative est manquante.";

        /** Reading the data ******************************************************************************************/

        $data = [];
        while ($content = fgetcsv($handle, null, ";")) {
            $data[] = $content;
        }

        /** Checking for connected entities ***************************************************************************/

        $disciplines = [];
        $disciplines[""] = null;
        if ($positionDiscipline !== false) {
            foreach ($data as $item) {
                $libelle = ($item[$positionDiscipline]) ?? null;
                if ($libelle !== null and $libelle !== "" and !isset($disciplines[$libelle])) {
                    $discipline = $this->getCompetenceDisciplineService()->getCompetenceDisciplineByLibelle($libelle);
                    if ($discipline === null) {
                        $discipline = new CompetenceDiscipline();
                        $discipline->setLibelle($libelle);
                        $info[] = "Nouvelle discipline : " . $libelle;
                    }
                    $disciplines[$libelle] = $discipline;
                }
            }
        }

        $themes = [];
        if ($positionType !== false) {
            foreach ($data as $item) {
                if ($positionTheme !== false) {
                    $libelle = trim($item[$positionTheme]);
                    if (!isset($themes[$libelle])) {
                        $type = $this->getCompetenceThemeService()->getCompetenceThemeByLibelle($libelle);
                        if ($type === null and $libelle !== "") {
                            $type = new CompetenceTheme();
                            $type->setLibelle($libelle);
                            $info[] = "Nouveau thème : " . $libelle;
                        }
                        $themes[$libelle] = $type;
                    }
                }
            }
        }

        $types = [];
        if ($positionType !== false) {
            foreach ($data as $item) {
                $oldLibelle = trim($item[$positionType]);
                if (!isset($types[$oldLibelle])) {
                    $libelle = strtolower($oldLibelle);
                    $type = $this->getCompetenceTypeService()->getCompetenceTypeByCode($dictionnairesTypes[$libelle]);
                    if ($type === null and $libelle !== "") {
                        $type = new CompetenceType();
                        $type->setLibelle($libelle);
                        $info[] = "Nouveau type : " . $libelle;
                    }
                    $types[$oldLibelle] = $type;
                }
            }
        }

        /** Reading competence ****************************************************************************************/

        $competences = [];
        $oldSynonymes = [];
        if ($positionId !== false and $positionLibelle !== false and $positionType !== false) {
            $nLine = 1;
            foreach ($data as $item) {
                $id = $item[$positionId];
                $competence = $this->getCompetenceByRefentiel($referentiel, $id);
                if ($competence === null and $libelle !== "") {
                    $competence = new Competence();
                } else {
                    $old = $competence->getSynonymes(); foreach ($old as $synonyme) $oldSynonymes[] = $synonyme;
                    $competence->clearSynonymes();

                    if ($competence->getLibelle() !== $item[$positionLibelle]) $info[] = "Mise à jour du libellé de la compétence [libelle:" . $competence->getLibelle() . "]";
                    if ($competence->getType() !== $types[$item[$positionType]]) $info[] = "Mise à jour du type de la compétence [libelle:" . $competence->getLibelle() . "]";
                    if ($positionTheme !== false and $competence->getTheme() !== $themes[$item[$positionTheme]]) $info[] = "Mise à jour du thème de la compétence [libelle:" . $competence->getLibelle() . "]";
                    if ($positionDiscipline !== false and $competence->getDiscipline() !== $disciplines[$item[$positionDiscipline]]) $info[] = "Mise à jour de la discipline de la compétence [libelle:" . $competence->getLibelle() . "]";
                    if ($positionSynonyme !== false and $competence->getSynonymes() !== (($item[$positionSynonyme] !== '') ? $item[$positionSynonyme] : null)) $info[] = "Mise à jour de la liste des synonymes de la compétence [libelle:" . $competence->getLibelle() . "]";
                    if ($positionDefinition !== false and $competence->getDescription() !== (($item[$positionDefinition] !== '') ? $item[$positionDefinition] : null)) $info[] = "Mise à jour de la définition de la compétence [libelle:" . $competence->getLibelle() . "]";
                }
                // obligatoire
                $competence->setReferentiel($referentiel);
                $competence->setReference($id);
                $competence->setLibelle(trim($item[$positionLibelle]));
                $type = $types[$item[$positionType]];
                $competence->setType($type);
                // facultatif
                if ($positionTheme !== false) {
                    $theme = $themes[$item[$positionTheme]];
                    $competence->setTheme($theme);
                }
                if ($positionDefinition !== false) {
                    $competence->setDescription($item[$positionDefinition] !== "" ? $item[$positionDefinition] : null);
                }
                if ($positionDiscipline !== false and ($item[$positionDiscipline] ?? null) !== null) {
                    $discipline = $disciplines[$item[$positionDiscipline]];
                    $competence->setDiscipline($discipline);
                }
                if ($positionSynonyme !== false) {
                    if ($item[$positionSynonyme] !== "") {
                        $liste = explode($SEP_SYNONYME, $item[$positionSynonyme]);
                        foreach ($liste as $synonyme) {
                            $eSynonyme = new CompetenceSynonyme();
                            $eSynonyme->setCompetence($competence);
                            $eSynonyme->setLibelle($synonyme);
                            $competence->addSynonyme($eSynonyme);
                        }
                    }
                }
                if ($positionCodesFonction !== false) {
                    if ($item[$positionCodesFonction] !== "") {
                        $competence->setCodesFonction(str_replace(",","|",$item[$positionCodesFonction]));
                    }
                }
                if ($positionCodesEmploiType !== false) {
                    if ($item[$positionCodesEmploiType] !== "") {
                        $competence->setCodesEmploiType(str_replace(",","|",$item[$positionCodesEmploiType]));
                    }
                }

                $raw = [];
                foreach ($header as $position => $element) {
                    $raw[$element] = $item[$position];
                }
                $raw = json_encode($raw, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                $competence->setRaw($raw);
                $competences[$nLine++] = $competence;
            }

            if ($mode === 'import') {
                foreach ($oldSynonymes as $synonyme) {
                    $this->getObjectManager()->remove($synonyme);
                    $this->getObjectManager()->flush($synonyme);
                }
                foreach ($types as $type) {
                    if ($type->getId() === null) {
                        $this->getCompetenceTypeService()->create($type);
                        $info[] = "Création du type de compétences [libelle:" . $type->getLibelle() . "]";
                    }
                }
                foreach ($themes as $theme) {
                    if ($theme !== null and $theme->getId() === null) {
                        $this->getCompetenceThemeService()->create($theme);
                        $info[] = "Création du thème de compétences [libelle:" . $theme->getLibelle() . "]";
                    }
                }
                foreach ($disciplines as $discipline) {
                    if ($discipline !== null and $discipline->getId() === null) {
                        $this->getCompetenceDisciplineService()->create($discipline);
                        $info[] = "Création de la discipline de compétences [libelle:" . $discipline->getLibelle() . "]";
                    }
                }
                foreach ($competences as $competence) {
                    $synonymes = $competence->getSynonymes();
                    $competence->clearSynonymes();
                    if ($competence->getId() === null) {
                        $this->create($competence);
                        $info[] = "Création de la compétence [libelle:" . $competence->getLibelle() . "]";
                    } else {
                        //clear synonymes ???

                        $this->update($competence);
//                        $info[] = "Mise à jour de la compétence [libelle:".$competence->getLibelle()."]";
                    }
                    foreach ($synonymes as $synonyme) {
                        if ($synonyme->getId() === null) {
                            $this->getObjectManager()->persist($synonyme);
                        }
                        $this->getObjectManager()->flush($synonyme);
                        $competence->addSynonyme($synonyme);
                    }
                }
            }
        } else {
            $error[] = "Au moins une colonne obligatoire est manquante !";
        }
        fclose($handle);

        $results = [
            'positions' => $positions,
            'data' => $data,
            'disciplines' => $disciplines,
            'themes' => $themes,
            'types' => $types,
            'competences' => $competences,
        ];

        return $results;
    }


}