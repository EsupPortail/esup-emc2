<?php

namespace EntretienProfessionnel\Service\Observation;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\Observation;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class ObservationService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function create(Observation $observation) : Observation
    {
        $this->getObjectManager()->persist($observation);
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function update(Observation $observation) : Observation
    {
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function historise(Observation $observation) : Observation
    {
        $observation->historiser();
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function restore(Observation $observation) : Observation
    {
        $observation->dehistoriser();
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function delete(Observation $observation) : Observation
    {
        $this->getObjectManager()->remove($observation);
        $this->getObjectManager()->flush($observation);
        return $observation;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Observation::class)->createQueryBuilder('observation')
            ->addSelect('entretien')->join('observation.entretien','entretien')
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Observation[]
     */
    public function getObservations(string $champ = 'id', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('observation.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Observation|null
     */
    public function getObservation(?int $id) : ?Observation
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observation.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnelObservation partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Observation|null
     */
    public function getRequestedObservation(AbstractActionController $controller, string $param = 'observation') : ?Observation
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getObservation($id);
        return $result;
    }
}