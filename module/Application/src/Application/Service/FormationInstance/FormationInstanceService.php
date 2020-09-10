<?php

namespace Application\Service\FormationInstance;

use Application\Entity\Db\Formation;
use Application\Entity\Db\FormationInstance;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function create(FormationInstance  $instance)
    {
        $this->createFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function update(FormationInstance  $instance)
    {
        $this->updateFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function historise(FormationInstance  $instance)
    {
        $this->historiserFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function restore(FormationInstance  $instance)
    {
        $this->restoreFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function delete(FormationInstance  $instance)
    {
        $this->deleteFromTrait($instance);
        return $instance;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb= $this->getEntityManager()->getRepository(FormationInstance::class)->createQueryBuilder('Finstance')
            ->addSelect('formation')->join('Finstance.formation', 'formation')
            ->addSelect('journee')->leftJoin('Finstance.journees', 'journee')
            ->addSelect('inscrit')->leftJoin('Finstance.inscrits', 'inscrit')
            ->addSelect('agent')->leftJoin('inscrit.agent', 'agent')
            ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return void
     */
    public function getFormationsInstances($champ = 'id', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('Finstance.' . $champ, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Formation $formation
     * @param string $champ
     * @param string $ordre
     * @return void
     */
    public function getFormationsInstancesByFormation(Formation $formation, $champ = 'id', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('finstance')->leftJoin('formation.instances', 'finstance')
            ->andWhere('formation.id = :id')
            ->setParameter('id', $formation->getId())
            ->orderBy('Finstance.' . $champ, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FormationInstance
     */
    public function getFormationInstance($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('Finstance.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstance partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationInstance
     */
    public function getRequestedFormationInstance($controller, $param='formation-instance')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationInstance($id);

        return $result;
    }
}