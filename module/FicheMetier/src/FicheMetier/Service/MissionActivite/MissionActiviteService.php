<?php

namespace FicheMetier\Service\MissionActivite;

use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class MissionActiviteService {
    use EntityManagerAwareTrait;


    /** Gestion des entités *******************************************************************************************/

    public function create(MissionActivite $activite) : MissionActivite
    {
        try {
            $this->getEntityManager()->persist($activite);
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en base",0 ,$e);
        }
        return $activite;
    }

    public function update(MissionActivite $activite) : MissionActivite
    {
        try {
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD",0,$e);
        }
        return $activite;
    }

    public function delete(MissionActivite $activite) : MissionActivite
    {
        try {
            $this->getEntityManager()->remove($activite);
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD",0,$e);
        }
        return $activite;
    }

    /** Querying ******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(MissionActivite::class)->createQueryBuilder('missionactivite');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder [".MissionActivite::class."]",0,$e);
        }
        return $qb;
    }

    public function getActivite(?int $id) : ?MissionActivite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("missionactivite.id = :id")->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".MissionActivite::class."] partagent le même id [".$id."]",0,$e);
        }

        return $result;
    }

    public function getRequestedActivite(AbstractActionController $controller, string $param='activite') : ?MissionActivite
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getActivite($id);
    }

    /** Facade ********************************************************************************************************/

}