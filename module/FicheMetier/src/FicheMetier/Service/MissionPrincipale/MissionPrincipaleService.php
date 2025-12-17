<?php

namespace FicheMetier\Service\MissionPrincipale;

use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use Laminas\Mvc\Controller\AbstractActionController;
use Metier\Entity\Db\FamilleProfessionnelle;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Referentiel\Entity\Db\Referentiel;
use RuntimeException;

class MissionPrincipaleService
{
    use ProvidesObjectManager;
    use FamilleProfessionnelleServiceAwareTrait;
//    use FicheMetierServiceAwareTrait;
    use NiveauServiceAwareTrait;

    /** GESTION DES ENTITES  ******************************************************************************************/

    public function create(Mission $mission): Mission
    {
        $this->getObjectManager()->persist($mission);
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function update(Mission $mission): Mission
    {
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function historise(Mission $mission): Mission
    {
        $mission->historiser();
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function restore(Mission $mission): Mission
    {
        $mission->dehistoriser();
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function delete(Mission $mission): Mission
    {
        $this->getObjectManager()->remove($mission);
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Mission::class)->createQueryBuilder('mission')
            ->leftJoin('mission.listeFicheMetierMission', 'listeFicheMetierMission')->addSelect('listeFicheMetierMission')
            ->leftJoin('mission.listeFichePosteMission', 'listeFichePosteMission')->addSelect('listeFichePosteMission')
            ->leftJoin('mission.activites', 'activite')->addSelect('activite')
            ->leftJoin('mission.famillesProfessionnelles', 'famille')->addSelect('famille')
            ->leftJoin('mission.referentiel', 'referentiel')->addSelect('referentiel')
        ;
        return $qb;
    }

    /** @return Mission[] */
    public function getMissionsPrincipales(bool $withHisto = false, string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderby('mission.' . $champ, $ordre);
        if (!$withHisto) $qb = $qb->andWhere('mission.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMissionPrincipale(?int $id): ?Mission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('mission.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Mission partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedMissionPrincipale(AbstractActionController $controller, string $param = 'mission-principale'): ?Mission
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMissionPrincipale($id);
        return $result;
    }


    public function getMissionPrincipaleByLibelle(?string $libelle): ?Mission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('mission.libelle = :libelle')->setParameter('libelle', $libelle);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            // todo probablement ajouter la notion de référentiel ...
            throw new RuntimeException("Plusieurs [" . Mission::class . "] partagent le même libellé [" . $libelle . "]", -1, $e );
        }
        return $result;
    }

    public function getMissionPrincipaleByReference(Referentiel $referentiel, string $reference): ?Mission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('mission.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
            ->andWhere('mission.reference = :reference')->setParameter('reference', $reference);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Mission::class . "] partagent la même référence [" . $referentiel->getLibelleCourt() . "-" . $reference . "]", -1, $e);
        }
        return $result;
    }

    /** @return Mission[]*/
    public function getMissionsPrincipalesWithFiltre(array $params = []): array
    {
        $qb = $this->createQueryBuilder();
        if (isset($params['referentiel']) AND $params['referentiel'] !== '') {
            $referentielId = $params['referentiel'];
            $qb = $qb->andWhere('referentiel.id = :referentiel')->setParameter('referentiel', $referentielId);
        }
        if (isset($params['famille']) AND $params['famille'] !== '') {
            $familleId = $params['famille'];
            $qb = $qb->andWhere('famille.id = :famille')->setParameter('famille', $familleId);
        }
        if (isset($params['histo']) AND $params['histo'] !== '') {
            if ($params['histo'] == 0) $qb = $qb->andWhere('mission.histoDestruction IS NULL');
            if ($params['histo'] == 1) $qb = $qb->andWhere('mission.histoDestruction IS NOT NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/


    /** @return Mission[] */
    public function findMissionsPrincipalesByExtendedTerm(string $texte): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(mission.libelle) like :search or LOWER(activite.libelle) like :search")
            ->andWhere('mission.histoDestruction IS NULL')
            ->andWhere('activite.histoDestruction IS NULL')
            ->setParameter('search', '%' . strtolower($texte) . '%');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function formatToJSON(array $missions): array
    {
        $result = [];
        /** @var Mission[] $missions */
        foreach ($missions as $mission) {
            $result[] = array(
                'id' => $mission->getId(),
                'label' => $mission->getLibelle(),
//                'description' => 'blabla bli bli',
//                'extra' => "<span class='badge' style='background-color: slategray;'>" .. "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    public function ajouterActivite(?Mission $mission, MissionActivite $activite): MissionActivite
    {
        $activite->setMission($mission);
        $activite->setOrdre(9999);
        $this->getObjectManager()->persist($activite);
        $this->compressActiviteOrdre($mission);
        $this->getObjectManager()->flush($activite);
        return $activite;
    }

    public function compressActiviteOrdre(Mission $mission): Mission
    {
        $activites = $mission->getActivites();
        usort($activites, function (MissionActivite $a, MissionActivite $b) {
            return $a->getOrdre() > $b->getOrdre();
        });

        $position = 1;
        foreach ($activites as $activite) {
            $activite->setOrdre($position);
            $this->getObjectManager()->flush($activite);
            $position++;
        }
        return $mission;
    }

    /** FACADE ********************************************************************************************************/

    public function createWith(string $intitule, array $activites, bool $perist = true): ?Mission
    {
        $mission = new Mission();
        $mission->setLibelle($intitule);
        if ($perist) $this->create($mission);

        $position = 1;
        foreach ($activites as $activite_) {
            $activite = new MissionActivite();
            $activite->setMission($mission);
            $activite->setLibelle($activite_);
            $activite->setOrdre($position);
            $position++;
            if ($perist) {
                $this->getObjectManager()->persist($activite);
            } else {
                $mission->addMissionActivite($activite);
            }
        }


        return $mission;
    }

    /** @return array (?Mission, string[], array) * */
    public function createOneWithCsv($json, string $separateur = '|', ?Referentiel $referentiel = null, ?int $position = null): array
    {
        $debugs = [
            'info' => [],
            'warning' => [],
            'error' => [],
        ];
        $to_create = [
            'familles' => [],
        ];

        /* LIBELLE ****************************************************************************************************/
        if (!isset($json[Mission::MISSION_PRINCIPALE_HEADER_LIBELLE]) or trim($json[Mission::MISSION_PRINCIPALE_HEADER_LIBELLE]) === '') {
            throw new RuntimeException("La colonne obligatoire [".Mission::MISSION_PRINCIPALE_HEADER_LIBELLE."] est manquante dans le fichier CSV sur la ligne [" . ($position ?? "non préciser") . "]");
        } else $libelle = trim($json[Mission::MISSION_PRINCIPALE_HEADER_LIBELLE]);
        if (!isset($json[Mission::MISSION_PRINCIPALE_HEADER_ID]) or trim($json[Mission::MISSION_PRINCIPALE_HEADER_ID]) === '') {
            throw new RuntimeException("La colonne obligatoire [".Mission::MISSION_PRINCIPALE_HEADER_ID."] est manquante dans le fichier CSV sur la ligne [" . ($position ?? "non préciser") . "]");
        } else $idOrig = trim($json[Mission::MISSION_PRINCIPALE_HEADER_ID]);


        /*Recupération ou creation */

        $mission = $this->getMissionPrincipaleByReference($referentiel, $json[Mission::MISSION_PRINCIPALE_HEADER_ID]);
        if ($mission === null) {
            $mission = new Mission();
        }

        $mission->setLibelle($libelle);
        $mission->setReferentiel($referentiel);
        $mission->setReference($idOrig);

        /* ACTIVITES **************************************************************************************************/
        if (isset($json[Mission::MISSION_PRINCIPALE_HEADER_ACTIVITES])) {
            $activites = explode($separateur, $json[Mission::MISSION_PRINCIPALE_HEADER_ACTIVITES]);
            $positionActivite = 0;
            $mission->clearActivites();
            foreach ($activites as $activite) {
                if (trim($activite) !== '' AND !$mission->hasActivite($activite)) {
                    $act = new MissionActivite();
                    $act->setLibelle($activite);
                    $act->setMission($mission);
                    $act->setOrdre(++$positionActivite);
                    $mission->addMissionActivite($act);
                }
            }
        }

        /* NIVEAUX ***************************************************************************************************/
        if (isset($json[Mission::MISSION_PRINCIPALE_HEADER_NIVEAU]) and trim($json[Mission::MISSION_PRINCIPALE_HEADER_NIVEAU]) !== '') {
            $niveau = explode($separateur, $json[Mission::MISSION_PRINCIPALE_HEADER_NIVEAU]);
            if (count($niveau) === 1) {
                $niv = $this->getNiveauService()->getNiveauByEtiquette(trim($niveau[0]));
                if ($niv === null) {
                    $debugs['warning'][] = "Le niveau [" . trim($niveau[0]) . "] n'existe pas (ligne " . $position . ").";
                } else {
                    $niveau_ = new NiveauEnveloppe();
                    $niveau_->setBorneInferieure($niv);
                    $niveau_->setBorneSuperieure($niv);
                    $mission->setNiveau($niveau_);
                }
            }
            if (count($niveau) === 2) {
                $inf = $this->getNiveauService()->getNiveauByEtiquette(trim($niveau[0]));
                if ($inf === null) {
                    $debugs['warning'][] = "Le niveau [" . trim($niveau[0]) . "] n'existe pas (ligne " . $position . ").";
                }
                $sup = $this->getNiveauService()->getNiveauByEtiquette(trim($niveau[1]));
                if ($sup === null) {
                    $debugs['warning'][] = "Le niveau [" . trim($niveau[1]) . "] n'existe pas (ligne " . $position . ").";
                }
                if ($inf !== null and $sup !== null) {
                    $niveau_ = new NiveauEnveloppe();
                    $niveau_->setBorneInferieure($inf);
                    $niveau_->setBorneSuperieure($sup);
                    $mission->setNiveau($niveau_);
                }
            }
        }

        /* FAMILLE PROFESSIONNELLE ***********************************************************************************/
        if (isset($json[Mission::MISSION_PRINCIPALE_HEADER_FAMILLES]) and trim($json[Mission::MISSION_PRINCIPALE_HEADER_FAMILLES]) !== '') {
            $mission->clearFamillesProfessionnelles();
            $famillesString = explode($separateur, $json[Mission::MISSION_PRINCIPALE_HEADER_FAMILLES]);
            foreach ($famillesString as $familleString) {
                $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle(trim($familleString));
                if ($famille === null) {
                    $famille = new FamilleProfessionnelle();
                    $famille->setLibelle(trim($familleString));
                    $debugs['warning'][] = "La famille professionnelle [" . trim($familleString) . "] n'existe pas (ligne " . $position . ") et est/sera créée.";
                    $to_create['familles'][] = trim($familleString);
                }
                if (!$mission->hasFamilleProfessionnelle($famille)) $mission->addFamilleProfessionnelle($famille);
            }
        }

        /* COMPLEMENT *************************************************************************************************/
        if (isset($json[Mission::MISSION_PRINCIPALE_HEADER_CODES_EMPLOITYPE]) and trim($json[Mission::MISSION_PRINCIPALE_HEADER_CODES_EMPLOITYPE]) !== '') {
            $mission->setCodesFicheMetier(trim($json[Mission::MISSION_PRINCIPALE_HEADER_CODES_EMPLOITYPE]));
        }
        if (isset($json[Mission::MISSION_PRINCIPALE_HEADER_CODES_FONCTION]) and trim($json[Mission::MISSION_PRINCIPALE_HEADER_CODES_FONCTION]) !== '') {
            $mission->setCodesFonction(trim($json[Mission::MISSION_PRINCIPALE_HEADER_CODES_FONCTION]));
        }

        /** SOURCE ****************************************************************************************************/
        $source_string = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $mission->setSourceString($source_string);

        return [$mission, $debugs, $to_create];

    }

    public function getMissionsPrincipalesAsOptions(): array
    {
        $missions = $this->getMissionsPrincipales();

        $options = [];
        foreach ($missions as $mission) {
            $options[$mission->getId()] = $this->missionOptionify($mission);
        }
        return $options;
    }

    private function missionOptionify(Mission $mission): array
    {
//        $texte  = $mission->getLibelle();
        $texte = "<span class='libelle_mission shorten'>" . MissionPrincipaleService::tronquerTexte($mission->getLibelle(), 100) . "</span>";
        $texte .= "<span class='libelle_mission full' style='display: none'>" . $mission->getLibelle() . "</span>";
        $description = null;

        if (!empty($mission->getActivites())) {
            $description = "<ul>";
            foreach ($mission->getActivites() as $activite) {
                $description .= "<li>" . htmlentities($activite->getLibelle()) . "</li>";
            }
            $description .= "</ul>";
        }

        $texte = "<span class='mission' title='" . ($description ?? "Aucune description") . "' class='badge btn-danger'>" . $texte;

        if ($mission->getCodesFicheMetier() !== null) {
            $texte .= "&nbsp;" . "<span class='badge'>".
                $mission->getCodesFicheMetier()
                . "</span>";
        }

        $texte .= "<span class='description' style='display: none' onmouseenter='alert(event.target);'>" . ($description ?? "Aucune description") . "</span>"
            . "</span>";

        $this_option = [
            'value' => $mission->getId(),
            'attributes' => [
                'data-content' => $texte
            ],
            'label' => $texte,
        ];
        return $this_option;
    }

    static public function tronquerTexte(?string $texte, int $limite = 80): string
    {
        if ($texte === null) return "";
        // Si le texte est plus court que la limite, on ne fait rien
        if (mb_strlen($texte) <= $limite) {
            return $texte;
        }

        // On tronque à la limite sans couper un mot
        $texteTronque = mb_substr($texte, 0, $limite);

        // Si on a coupé au milieu d’un mot, on recule jusqu’au dernier espace
        $dernierEspace = mb_strrpos($texteTronque, ' ');
        if ($dernierEspace !== false) {
            $texteTronque = mb_substr($texteTronque, 0, $dernierEspace);
        }

        return rtrim($texteTronque) . ' ...';
    }


}