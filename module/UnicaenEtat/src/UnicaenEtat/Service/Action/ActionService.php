<?php

namespace UnicaenEtat\Service\Action;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Entity\Db\Action;
use UnicaenEtat\Entity\Db\ActionType;
use UnicaenEtat\Entity\Db\Etat;
use Zend\Mvc\Controller\AbstractActionController;

class ActionService {
    use GestionEntiteHistorisationTrait;
    
    /** GESTION DES ENTITES *****************************/

    /**
     * @param Action $etat
     * @return Action
     */
    public function create(Action $etat)
    {
        $this->createFromTrait($etat);
        return $etat;
    }

    /**
     * @param Action $etat
     * @return Action
     */
    public function update(Action $etat)
    {
        $this->updateFromTrait($etat);
        return $etat;
    }

    /**
     * @param Action $etat
     * @return Action
     */
    public function historise(Action $etat)
    {
        $this->historiserFromTrait($etat);
        return $etat;
    }

    /**
     * @param Action $etat
     * @return Action
     */
    public function restore(Action $etat)
    {
        $this->restoreFromTrait($etat);
        return $etat;
    }

    /**
     * @param Action $etat
     * @return Action
     */
    public function delete(Action $etat)
    {
        $this->deleteFromTrait($etat);
        return $etat;
    }

    /** REQUETAGE ***************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        //todo ajouter la jointure sur les actions une fois celles-ci en place ...
        $qb = $this->getEntityManager()->getRepository(Action::class)->createQueryBuilder('action')
            ->addSelect('atype')->join('action.type', 'atype')
            ->addSelect('etat')->join('action.etat','etat')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Action[]
     */
    public function getActions($champ = 'code', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('action.' . $champ, $ordre)
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @return Action
     */
    public function getAction(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('action.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Action partagent le même id [".$id."].");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Action
     */
    public function getRequestedAction(AbstractActionController $controller, $param='action')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getAction($id);
    }

    /**
     * @param ActionType $type
     * @return Action[]
     */
    public function getActionsByType(ActionType $type)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('action.type = :type')
            ->setParameter('type', $type)
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Etat $etat
     * @return Action[]
     */
    public function getActionsByEtat(Etat $etat)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('action.etat = :etat')
            ->setParameter('etat', $etat)
        ;
        return $qb->getQuery()->getResult();
    }

}