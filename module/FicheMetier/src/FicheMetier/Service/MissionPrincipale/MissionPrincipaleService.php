<?php

namespace FicheMetier\Service\MissionPrincipale;

use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Competence;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use Laminas\Mvc\Controller\AbstractActionController;
use Metier\Entity\Db\FamilleProfessionnelle;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use RuntimeException;

class MissionPrincipaleService
{
    use ProvidesObjectManager;
    use FamilleProfessionnelleServiceAwareTrait;
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

            //            ->leftJoin('mission.applications', 'applicationelement')->addSelect('applicationelement')
            //            ->leftJoin('applicationelement.application', 'application')->addSelect('application')
            //            ->leftJoin('mission.competences', 'competenceelement')->addSelect('competenceelement')
            //            ->leftJoin('competenceelement.competence', 'competence')->addSelect('competence')
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


    public function getMissionsHavingCompetence(?Competence $competence)
    {
        $qb = $this->createQueryBuilder()
            ->leftJoin('mission.competences', 'competence')->addSelect('competence')
            ->andWhere('competence.id = :competenceId')->setParameter('competenceId', $competence->getId());
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
    public function createOneWithCsv($json, string $separateur = '|', ?int $position = null): array
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
        if (!isset($json['Libellé']) or trim($json['Libellé']) === '') {
            throw new RuntimeException("La colonne obligatoire [Libellé] est manquante dans le fichier CSV sur la ligne [" . ($position ?? "non préciser") . "]");
        } else $libelle = trim($json['Libellé']);

        $mission = new Mission();
        $mission->setLibelle($libelle);

        /* ACTIVITES **************************************************************************************************/
        if (isset($json['Activités associées'])) {
            $activites = explode($separateur, $json['Activités associées']);
            $positionActivite = 0;
            foreach ($activites as $activite) {
                if (trim($activite) !== '') {
                    $act = new MissionActivite();
                    $act->setLibelle($activite);
                    $act->setMission($mission);
                    $act->setOrdre(++$positionActivite);
                    $mission->addMissionActivite($act);
                }
            }
        }

        /* NIVEAUX ***************************************************************************************************/
        if (isset($json['Niveau']) and trim($json['Niveau']) !== '') {
            $niveau = explode($separateur, $json['Niveau']);
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
        if (isset($json['Familles professionnelles']) and trim($json['Familles professionnelles']) !== '') {
            $famillesString = explode($separateur, $json['Familles professionnelles']);
            foreach ($famillesString as $familleString) {
                $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle(trim($familleString));
                if ($famille === null) {
                    $famille = new FamilleProfessionnelle();
                    $famille->setLibelle(trim($familleString));
                    $debugs['warning'][] = "La famille professionnelle [" . trim($familleString) . "] n'existe pas (ligne " . $position . ") et est/sera créée.";
                    $to_create['familles'][] = trim($familleString);
                }
                $mission->addFamilleProfessionnelle($famille);
            }
        }

        /* COMPLEMENT *************************************************************************************************/
        if (isset($json['Complément']) and trim($json['Complément']) !== '') {
            $mission->setComplement(trim($json['Complément']));
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
        $texte = $mission->getLibelle();
        $description = null;

        if (!empty($mission->getActivites())) {
            $description = "<ul>";
            foreach ($mission->getActivites() as $activite) {
                $description .= "<li>" . htmlentities($activite->getLibelle()) . "</li>";
            }
            $description .= "</ul>";
        }

        $texte = "<span class='mission' title='" . ($description ?? "Aucune description") . "' class='badge btn-danger'>" . $texte;

        if ($mission->getComplement() !== null) {
            $texte .= "&nbsp;" . "<span class='badge'>"
                . $mission->getComplement()
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
}