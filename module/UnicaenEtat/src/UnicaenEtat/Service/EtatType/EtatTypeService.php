<?php

namespace UnicaenEtat\Service\EtatType;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Entity\Db\EtatType;
use Zend\Mvc\Controller\AbstractActionController;

class EtatTypeService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES  **********************************/

    /**
     * @param EtatType $type
     * @return EtatType
     */
    public function create(EtatType $type)
    {
        $this->createFromTrait($type);
        return $type;
    }

    /**
     * @param EtatType $type
     * @return EtatType
     */
    public function update(EtatType $type)
    {
        $this->updateFromTrait($type);
        return $type;
    }

    /**
     * @param EtatType $type
     * @return EtatType
     */
    public function historise(EtatType $type)
    {
        $this->historiserFromTrait($type);
        return $type;
    }

    /**
     * @param EtatType $type
     * @return EtatType
     */
    public function restore(EtatType $type)
    {
        $this->restoreFromTrait($type);
        return $type;
    }

    /**
     * @param EtatType $type
     * @return EtatType
     */
    public function delete(EtatType $type)
    {
        $this->deleteFromTrait($type);
        return $type;
    }

    /** REQUETAGE ************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(EtatType::class)->createQueryBuilder('etype')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return EtatType[]
     */
    public function getEtatTypes($champ = 'code', $ordre='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('etype.'.$champ, $ordre)
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getEtatTypesAsOptions($champ = 'code', $ordre = 'ASC')
    {
        $result = $this->getEtatTypes($champ,$ordre);
        $array = [];
        foreach ($result as $item) {
            $array[$item->getId()] = $item->getLibelle();
        }
        return $array;
    }

    /**
     * @param int $id
     * @return EtatType
     */
    public function getEtatType(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etype.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EtatType partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return EtatType
     */
    public function getRequestedEtatType(AbstractActionController $controller, $param='etat-type')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getEtatType($id);
    }
}