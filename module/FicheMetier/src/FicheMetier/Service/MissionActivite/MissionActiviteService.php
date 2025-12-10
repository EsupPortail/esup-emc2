<?php

namespace FicheMetier\Service\MissionActivite;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class MissionActiviteService
{
    use ProvidesObjectManager;


    /** Gestion des entités *******************************************************************************************/

    public function create(MissionActivite $activite): MissionActivite
    {
        $this->getObjectManager()->persist($activite);
        $this->getObjectManager()->flush($activite);
        return $activite;
    }

    public function update(MissionActivite $activite): MissionActivite
    {
        $this->getObjectManager()->flush($activite);
        return $activite;
    }

    public function delete(MissionActivite $activite): MissionActivite
    {
        $this->getObjectManager()->remove($activite);
        $this->getObjectManager()->flush($activite);
        return $activite;
    }

    /** Querying ******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(MissionActivite::class)->createQueryBuilder('missionactivite');
        return $qb;
    }

    public function getActivite(?int $id): ?MissionActivite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("missionactivite.id = :id")->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . MissionActivite::class . "] partagent le même id [" . $id . "]", 0, $e);
        }

        return $result;
    }

    public function getRequestedActivite(AbstractActionController $controller, string $param = 'activite'): ?MissionActivite
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getActivite($id);
    }

    /** @return MissionActivite[] */
    public function getActivites(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) { $qb = $qb->andWhere("missionactivite.histoDestruction IS NULL"); }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** Facade ********************************************************************************************************/

    /** @return MissionActivite[] */
    public function transforms(Mission $mission, string $activitesAsList): array
    {
        // quid ne prendre que ce qui est compris entre les balises ul ?
        $missionsActivites = [];
        if (str_starts_with($activitesAsList,"<ul>") AND str_ends_with($activitesAsList,"</ul>")) {
            // transforme la liste à puce HTML en un tableau de libellé
            $activitesAsList = substr($activitesAsList, 4, -5);
            $activitesAsList = str_replace("<li>", "", $activitesAsList);
            $activitesAsList = str_replace("\n", "", $activitesAsList);
            $activitesAsList = str_replace("\t", "", $activitesAsList);
            $activitesAsList = str_replace("\r", "", $activitesAsList);
            $activitesAsList = str_replace("&nbsp;", " ", $activitesAsList);
            $activitesAsList = explode("</li>", $activitesAsList);
            $activitesAsList = array_filter($activitesAsList, function ($item) { return trim($item) !== ''; });

            // check si il existe et crée au besoin
            foreach ($activitesAsList as $activite) {
                $missionActivite = $mission->getActivite($activite);
                if ($missionActivite !== null) $missionsActivites[] = $missionActivite;
                else {
                    $missionActivite = new MissionActivite();
                    $missionActivite->setMission($mission);
                    $missionActivite->setLibelle($activite);
                    $missionsActivites[] = $missionActivite;
                }
            }
            // met à jour les positions
            $position = 1;
            foreach ($missionsActivites as $missionActivite) {
                $missionActivite->setOrdre($position++);
            }
        }
        return $missionsActivites;
    }
}