<?php

namespace FicheMetier\Service\MissionActivite;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
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

}