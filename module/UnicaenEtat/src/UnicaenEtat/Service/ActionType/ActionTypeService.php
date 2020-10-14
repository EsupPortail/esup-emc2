<?php

namespace UnicaenEtat\Service\ActionType;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Entity\Db\ActionType;
use Zend\Mvc\Controller\AbstractActionController;

class ActionTypeService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES  **********************************/

    /**
     * @param ActionType $type
     * @return ActionType
     */
    public function create(ActionType $type)
    {
        $this->createFromTrait($type);
        return $type;
    }

    /**
     * @param ActionType $type
     * @return ActionType
     */
    public function update(ActionType $type)
    {
        $this->updateFromTrait($type);
        return $type;
    }

    /**
     * @param ActionType $type
     * @return ActionType
     */
    public function historise(ActionType $type)
    {
        $this->historiserFromTrait($type);
        return $type;
    }

    /**
     * @param ActionType $type
     * @return ActionType
     */
    public function restore(ActionType $type)
    {
        $this->restoreFromTrait($type);
        return $type;
    }

    /**
     * @param ActionType $type
     * @return ActionType
     */
    public function delete(ActionType $type)
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
        $qb = $this->getEntityManager()->getRepository(ActionType::class)->createQueryBuilder('atype')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return ActionType[]
     */
    public function getActionTypes($champ = 'code', $ordre='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('atype.'.$champ, $ordre)
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getActionTypesAsOptions($champ = 'code', $ordre = 'ASC')
    {
        $result = $this->getActionTypes($champ,$ordre);
        $array = [];
        foreach ($result as $item) {
            $array[$item->getId()] = $item->getLibelle();
        }
        return $array;
    }

    /**
     * @param int $id
     * @return ActionType
     */
    public function getActionType(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('atype.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ActionType partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ActionType
     */
    public function getRequestedActionType(AbstractActionController $controller, $param='action-type')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getActionType($id);
    }
}