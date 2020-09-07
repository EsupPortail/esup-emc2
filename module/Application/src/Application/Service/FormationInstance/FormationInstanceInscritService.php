<?php

namespace Application\Service\FormationInstance;

use Application\Entity\Db\FormationInstanceInscrit;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceInscritService {
    use GestionEntiteHistorisationTrait;

    /** GESTION ENTITES ****************************************************************************************/

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function create(FormationInstanceInscrit $inscrit)
    {
        $this->createFromTrait($inscrit);
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function update(FormationInstanceInscrit $inscrit)
    {
        $this->updateFromTrait($inscrit);
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function historise(FormationInstanceInscrit $inscrit)
    {
        $this->historiserFromTrait($inscrit);
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function restore(FormationInstanceInscrit $inscrit)
    {
        $this->restoreFromTrait($inscrit);
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function delete(FormationInstanceInscrit $inscrit)
    {
        $this->deleteFromTrait($inscrit);
        return $inscrit;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FormationInstanceInscrit::class)->createQueryBuilder('inscrit')
            ->addSelect('agent')->join('inscrit.agent', 'agent')
            ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->addSelect('finstance')->join('inscrit.instance', 'finstance')
            ->addSelect('formation')->join('finstance.formation', 'formation')
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FormationInstanceInscrit
     */
    public function getFormationsInstancesInscrits($champ = 'id', $ordre = 'ASC') 
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('inscrit.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
    
    /**
     * @param integer $id
     * @return FormationInstanceInscrit
     */
    public function getFormationInstanceInscrit($id)
    {
        $qb  = $this->createQueryBuilder()
            ->andWhere('inscrit.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstanceInscrit partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationInstanceInscrit
     */
    public function getRequestedFormationInstanceInscrit($controller, $param = 'inscrit')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationInstanceInscrit($id);
        return $result;
    }
}