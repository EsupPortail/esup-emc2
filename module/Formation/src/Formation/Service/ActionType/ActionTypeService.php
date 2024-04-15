<?php

namespace  Formation\Service\ActionType;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\ActionType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ActionTypeService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(ActionType $actionType): ActionType
    {
        $this->getObjectManager()->persist($actionType);
        $this->getObjectManager()->flush();
        return $actionType;
    }

    public function update(ActionType $actionType): ActionType
    {
        $this->getObjectManager()->flush();
        return $actionType;
    }

    public function historise(ActionType $actionType): ActionType
    {
        $actionType->historiser();
        $this->getObjectManager()->flush();
        return $actionType;
    }

    public function restore(ActionType $actionType): ActionType
    {
        $actionType->dehistoriser();
        $this->getObjectManager()->flush();
        return $actionType;
    }

    public function delete(ActionType $actionType): ActionType
    {
        $this->getObjectManager()->remove($actionType);
        $this->getObjectManager()->flush();
        return $actionType;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ActionType::class)->createQueryBuilder('actiontype')
            ->leftJoin('actiontype.actions', 'action')
        ;
        return $qb;
    }

    public function getActionType(?int $id): ?ActionType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('actiontype.id = :id')->setParameter('id', $id)
        ;
        try {
            $actionType = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ActionType::class."] partagent le même id [".$id."]");
        }
        return $actionType;
    }

    public function getRequestedActionType(AbstractActionController $controller, string $param='action-type'): ?ActionType
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getActionType($id);
    }

    /** @return ActionType[] */
    public function getActionsTypes(string $champ='libelle', string $ordre='ASC', bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('actiontype.'.$champ, $ordre);

        if (!$withHisto) $qb = $qb->andWhere('actiontype.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getActionsTypesAsOptions(string $champ='libelle', string $ordre='ASC', bool $withHisto = false): array
    {
        $actionstypes = $this->getActionsTypes($champ, $ordre, $withHisto);
        $options = [];
        foreach ($actionstypes as $actiontype) {
            $options[$actiontype->getId()] = $actiontype->getLibelle();
        }
        return $options;
    }

    /** FACADE ********************************************************************************************************/

}