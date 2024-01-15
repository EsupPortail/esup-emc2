<?php

namespace Observation\Service\Observation;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Observation\Entity\Db\Observation;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ObservationService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES ************************************************************/

    public function create(Observation $observation): Observation
    {
        $this->getObjectManager()->persist($observation);
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    public function update(Observation $observation): Observation
    {
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    public function historise(Observation $observation): Observation
    {
        $observation->historiser();
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    public function restore(Observation $observation): Observation
    {
        $observation->dehistoriser();
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    public function delete(Observation $observation): Observation
    {
        $this->getObjectManager()->remove($observation);
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    /** REQUETAGE **********************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Observation::class)->createQueryBuilder('observation')
            ->leftJoin('observation.type', 'type')->addSelect('type')
            ->leftJoin('observation.validations', 'validation')->addSelect('validation')
        ;
        return $qb;
    }

    public function getObservation(?int $id): ?Observation
    {
        $qb  = $this->createQueryBuilder()
            ->andWhere('observation.id = :id')->setParameter('id', $id);
        try {
            $observation = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Observation::class."] partagent le même id [".$id."]",0,$e);
        }
        return $observation;
    }

    public function getRequestedObservation(AbstractActionController $controller, string $param="observation"): ?Observation
    {
        $id = $controller->params()->fromRoute($param);
        $observation = $this->getObservation($id);
        return $observation;
    }

    /** @return Observation[] */
    public function getObservations(string $champ='code', string $ordre='ASC', bool $histo = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('observation.'. $champ, $ordre);

        if (!$histo) $qb = $qb->andWhere('observation.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE *************************************************************************/

}