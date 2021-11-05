<?php

namespace EntretienProfessionnel\Service\Observation;

use EntretienProfessionnel\Entity\Db\Observation;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ObservationService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function create(Observation $observation) : Observation
    {
        $this->createFromTrait($observation);
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function update(Observation $observation) : Observation
    {
        $this->updateFromTrait($observation);
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function historise(Observation $observation) : Observation
    {
        $this->historiserFromTrait($observation);
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function restore(Observation $observation) : Observation
    {
        $this->restoreFromTrait($observation);
        return $observation;
    }

    /**
     * @param Observation $observation
     * @return Observation
     */
    public function delete(Observation $observation) : Observation
    {
        $this->deleteFromTrait($observation);
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