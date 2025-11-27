<?php

namespace Element\Service\Competence;

use DateTime;
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
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CompetenceService
{
    use ProvidesObjectManager;
    use CompetenceDisciplineServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;

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
            ->addSelect('discipline')->leftJoin('competence.discipline', 'discipline');
        return $qb;
    }

    /** Competence[] */
    public function getCompetences(string $champ = 'libelle', string $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('competence.' . $champ, $order);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCompetencesByType(?CompetenceType $type = null): array
    {
        $qb = $this->createQueryBuilder();
        if ($type !== null) $qb = $qb->where('competence.type = :type')->setParameter('type', $type);

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

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCompetencesByTypes(): array
    {
        $competences = $this->getCompetences();

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
            $competences = array_filter($competences, function (Competence $competence) { return $competence->getType() AND $competence->getType()->getCode() !== CompetenceType::CODE_SPECIFIQUE;});
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
                    return $a->getLibelle() <=> $b->getLibelle();
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
                    return $a->getLibelle() <=> $b->getLibelle();
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
        /** @var CompetenceReferentiel $referentiel */
        $referentiel = $competence->getReferentiel();
        $type = $competence->getType();
        $texte = $competence->getLibelle();
        $this_option = [
            'value' => $competence->getId(),
            'attributes' => [
                'data-content' => "<span class='competence' title='" . ($competence->getDescription() ?? "Aucune description") . "' class='badge btn-danger'>" . $texte
                    . "&nbsp;" . "<span class='badge'>"
                    . (($type !== null) ? $type->getLibelle() : "Sans type")
                    . "</span>"
                    . "&nbsp;" . "<span class='badge' style='background: " . (($referentiel !== null) ? $referentiel->getCouleur() : "gray") . "'>"
                    . (($referentiel !== null) ? $referentiel->getLibelleCourt() : "Sans référentiel") . " - " . $competence->getIdSource()
                    . "</span>"
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
    public function getCompetencesByRefentiel(?CompetenceReferentiel $referentiel): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.referentiel = :referentiel')
            ->setParameter('referentiel', $referentiel);
        $result = $qb->getQuery()->getResult();

        /** @var Competence[] $result */
        $competences = [];
        foreach ($result as $item) {
            $competences[$item->getIdSource()] = $item;
        }
        return $competences;
    }

    public function getCompetenceByRefentiel(CompetenceReferentiel $referentiel, string $id): ?Competence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.referentiel = :referentiel')
            ->setParameter('referentiel', $referentiel)
            ->andWhere('competence.idSource = :id')
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

    public function createWith(string $libelle, ?string $description, ?CompetenceType $type, ?CompetenceTheme $theme, CompetenceReferentiel $referentiel, string $id, bool $persist = true): Competence
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
        $competence->setIdSource($id);
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

    private function getCompetenceMaxIdByRefentiel(CompetenceReferentiel $referentiel)
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
                'extra' => ($referentiel === null) ? "<span class='badge' style='background-color: slategray;'>Aucun référentiel</span>" : "<span class='badge' style='background-color: " . $referentiel->getCouleur() . ";'>" . $referentiel->getLibelleCourt() . " #" . $competence->getIdSource() . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    public function import(string $filepath, CompetenceReferentiel $referentiel, string $mode, array &$info, array &$warning, array &$error): array
    {
        $handle = fopen($filepath, "r");

        /** Fetching the header ***************************************************************************************/
        $header = fgetcsv($handle, null, ";");
        // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
        $header[0] = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header[0]);

        // Note il y a une typo dans le csv de Referens3 ...
        $positionId = array_search("Id_compérence", $header);
        if ($positionId === false) {
            $positionId = array_search("Id_compétence", $header);
        }
        $positionLibelle = array_search("Compétence", $header);
        $positionType = array_search("Registre", $header);
        $positionTheme = array_search("Domaine", $header);
        $positionDiscipline = array_search("Discipline", $header);
        $positionDefinition = array_search("Définition", $header);
        $positionEmploiType = array_search("id_Emplois_types_RéFérens", $header);
        $positionSynonyme = array_search("Synonymes", $header);

        $positions = ['id' => $positionId, 'libelle' => $positionLibelle, 'theme' => $positionTheme, 'type' => $positionType, 'discipline' => $positionDiscipline, 'definition' => $positionDefinition, 'emploi_type' => $positionEmploiType];

        if ($positionId === false) $error[] = "La colonne <code>Id_compétence</code> obligatoire est manquante !";
        if ($positionLibelle === false) $error[] = "La colonne <code>Compétence</code> obligatoire est manquante !";
        if ($positionType === false) $error[] = "La colonne <code>Registre</code> obligatoire est manquante !";
        if ($positionTheme === false) $warning[] = "La colonne <code>Domaine</code> facultative est manquante !";
        if ($positionDefinition === false) $warning[] = "La colonne <code>Définition</code> facultative est manquante !";
        if ($positionDiscipline === false) $warning[] = "La colonne <code>Discipline</code> facultative est manquante !";
        if ($positionSynonyme === false) $warning[] = "La colonne <code>Synonymes</code> facultative est manquante !";

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
                        $info[] = "Nouvelle Discipline : [" . $libelle . "]";
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
                $libelle = trim($item[$positionType]);
                if (!isset($types[$libelle])) {
                    $type = $this->getCompetenceTypeService()->getCompetenceTypeByLibelle($libelle);
                    if ($type === null and $libelle !== "") {
                        $type = new CompetenceType();
                        $type->setLibelle($libelle);
                        $info[] = "Nouveau type : " . $libelle;
                    }
                    $types[$libelle] = $type;
                }
            }
        }

        /** Reading competence ****************************************************************************************/

        $competences = []; $oldSynonymes = [];
        if ($positionId !== false and $positionLibelle !== false and $positionType !== false) {
            $nLine = 1;
            foreach ($data as $item) {
                $id = $item[$positionId];
                $competence = $this->getCompetenceByRefentiel($referentiel, $id);
                if ($competence === null and $libelle !== "") {
                    $competence = new Competence();
                } else {
                    $oldSynonymes = $competence->getSynonymes();
                    $competence->clearSynonymes();

                    if ($competence->getLibelle() !== $item[$positionLibelle]) $info[] = "Mise à jour du libellé de la compétence [id:" . $competence->getId() . " | libelle:" . $competence->getLibelle() . "]";
                    if ($competence->getType() !== $types[$item[$positionType]]) $info[] = "Mise à jour du type de la compétence [id:" . $competence->getId() . " | libelle:" . $competence->getLibelle() . "]";
                    if ($positionTheme !== false and $competence->getTheme() !== $themes[$item[$positionTheme]]) $info[] = "Mise à jour du thème de la compétence [id:" . $competence->getId() . " | libelle:" . $competence->getLibelle() . "]";
                    if ($positionDiscipline !== false and $competence->getDiscipline() !== $disciplines[$item[$positionDiscipline]]) $info[] = "Mise à jour de la discipline de la compétence [id:" . $competence->getId() . " | libelle:" . $competence->getLibelle() . "]";
                    if ($positionSynonyme !== false and $competence->getSynonymes() !== (($item[$positionSynonyme] !== '') ? $item[$positionSynonyme] : null)) $info[] = "Mise à jour de la liste des synonymes de la compétence [id:" . $competence->getId() . " | libelle:" . $competence->getLibelle() . "]";
                    if ($positionDefinition !== false and $competence->getDescription() !== (($item[$positionDefinition] !== '') ? $item[$positionDefinition] : null)) $info[] = "Mise à jour de la définition de la compétence [id:" . $competence->getId() . " | libelle:" . $competence->getLibelle() . "]";
                }
                // obligatoire
                $competence->setReferentiel($referentiel);
                $competence->setIdSource($id);
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
                if ($positionEmploiType !== false) {
                    $competence->setEmploisTypes($item[$positionEmploiType]);
                }
                if ($positionSynonyme !== false) {
                    if ($item[$positionSynonyme] !== "") {
                        $liste = explode(";", $item[$positionSynonyme]);
                        foreach ($liste as $synonyme) {
                            $eSynonyme = new CompetenceSynonyme();
                            $eSynonyme->setCompetence($competence);
                            $eSynonyme->setLibelle($synonyme);
                            $competence->addSynonyme($eSynonyme);
                        }
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
                        $info[] = "Création du type de compétences [id:" . $type->getId() . " | libelle:" . $type->getLibelle() . "]";
                    }
                }
                foreach ($themes as $theme) {
                    if ($theme !== null and $theme->getId() === null) {
                        $this->getCompetenceThemeService()->create($theme);
                        $info[] = "Création du thème de compétences [id:" . $theme->getId() . "libelle:" . $theme->getLibelle() . "]";
                    }
                }
                foreach ($disciplines as $discipline) {
                    if ($discipline !== null and $discipline->getId() === null) {
                        $this->getCompetenceDisciplineService()->create($discipline);
                        $info[] = "Création de la discipline de compétences [id:" . $discipline->getId() . "libelle:" . $discipline->getLibelle() . "]";
                    }
                }
                foreach ($competences as $competence) {
                    $synonymes = $competence->getSynonymes();
                    $competence->clearSynonymes();
                    if ($competence->getId() === null) {
                        $this->create($competence);
                        $info[] = "Création de la compétence [id:" . $competence->getId() . "libelle:" . $competence->getLibelle() . "]";
                    } else {
                        //clear synonymes ???

                        $this->update($competence);
//                        $info[] = "Mise à jour de la compétence [id:".$competence->getId()."libelle:".$competence->getLibelle()."]";
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