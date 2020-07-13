<?php

namespace Application\Service\EntretienProfessionnel;

use Application\Entity\Db\EntretienProfessionnelObservation;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class EntretienProfessionnelObservationService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param EntretienProfessionnelObservation $observation
     * @return EntretienProfessionnelObservation
     */
    public function create(EntretienProfessionnelObservation $observation)
    {
        $this->createFromTrait($observation);
        return $observation;
    }

    /**
     * @param EntretienProfessionnelObservation $observation
     * @return EntretienProfessionnelObservation
     */
    public function update(EntretienProfessionnelObservation $observation)
    {
        $this->updateFromTrait($observation);
        return $observation;
    }

    /**
     * @param EntretienProfessionnelObservation $observation
     * @return EntretienProfessionnelObservation
     */
    public function historise(EntretienProfessionnelObservation $observation)
    {
        $this->historiserFromTrait($observation);
        return $observation;
    }

    /**
     * @param EntretienProfessionnelObservation $observation
     * @return EntretienProfessionnelObservation
     */
    public function restore(EntretienProfessionnelObservation $observation)
    {
        $this->restoreFromTrait($observation);
        return $observation;
    }

    /**
     * @param EntretienProfessionnelObservation $observation
     * @return EntretienProfessionnelObservation
     */
    public function delete(EntretienProfessionnelObservation $observation)
    {
        $this->deleteFromTrait($observation);
        return $observation;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnelObservation::class)->createQueryBuilder('observation')
            ->addSelect('entretien')->join('observation.entretien','entretien')
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return EntretienProfessionnelObservation[]
     */
    public function getEntretienProfessionnelObservations($champ = 'id', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('observation.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param $id
     * @return EntretienProfessionnelObservation
     */
    public function getEntretienProfessionnelObservation($id)
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
     * @return EntretienProfessionnelObservation
     */
    public function getRequestedEntretienProfessionnelObservation($controller, $param = 'observation')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getEntretienProfessionnelObservation($id);
        return $result;
    }
}