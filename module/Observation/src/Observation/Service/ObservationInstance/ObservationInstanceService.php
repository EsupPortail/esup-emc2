<?php

namespace Observation\Service\ObservationInstance;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Observation\Entity\Db\ObservationInstance;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ObservationInstanceService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES ************************************************************/

    public function create(ObservationInstance $observation): ObservationInstance
    {
        $this->getObjectManager()->persist($observation);
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    public function update(ObservationInstance $observation): ObservationInstance
    {
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    public function historise(ObservationInstance $observation): ObservationInstance
    {
        $observation->historiser();
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    public function restore(ObservationInstance $observation): ObservationInstance
    {
        $observation->dehistoriser();
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    public function delete(ObservationInstance $observation): ObservationInstance
    {
        $this->getObjectManager()->remove($observation);
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    /** REQUETAGE **********************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ObservationInstance::class)->createQueryBuilder('observation')
            ->leftJoin('observation.type', 'type')->addSelect('type')
            ->leftJoin('observation.validations', 'validation')->addSelect('validation')
        ;
        return $qb;
    }

    public function getObservationInstance(?int $id): ?ObservationInstance
    {
        $qb  = $this->createQueryBuilder()
            ->andWhere('observation.id = :id')->setParameter('id', $id);
        try {
            $observation = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ObservationInstance::class."] partagent le même id [".$id."]",0,$e);
        }
        return $observation;
    }

    public function getRequestedObservationInstance(AbstractActionController $controller, string $param="observation-instance"): ?ObservationInstance
    {
        $id = $controller->params()->fromRoute($param);
        $observation = $this->getObservationInstance($id);
        return $observation;
    }

    /** @return ObservationInstance[] */
    public function getObservationsInstances(string $champ='code', string $ordre='ASC', bool $histo = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('observation.'. $champ, $ordre);

        if (!$histo) $qb = $qb->andWhere('observation.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE *************************************************************************/

}