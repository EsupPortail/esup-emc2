<?php

namespace EntretienProfessionnel\Service\Observation;

use Doctrine\ORM\ORMException;
use EntretienProfessionnel\Entity\Db\Observation;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ObservationService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function create(Observation $observation) : Observation
    {
        try {
            $this->getEntityManager()->persist($observation);
            $this->getEntityManager()->flush($observation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function update(Observation $observation) : Observation
    {
        try {
            $this->getEntityManager()->flush($observation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function historise(Observation $observation) : Observation
    {
        try {
            $observation->historiser();
            $this->getEntityManager()->flush($observation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function restore(Observation $observation) : Observation
    {
        try {
            $observation->dehistoriser();
            $this->getEntityManager()->flush($observation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function delete(Observation $observation) : Observation
    {
        try {
            $this->getEntityManager()->remove($observation);
            $this->getEntityManager()->flush($observation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $observation;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Observation::class)->createQueryBuilder('observation')
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