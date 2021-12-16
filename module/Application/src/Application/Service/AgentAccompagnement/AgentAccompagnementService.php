<?php

namespace Application\Service\AgentAccompagnement;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAccompagnement;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class AgentAccompagnementService {
    use GestionEntiteHistorisationTrait;

    /** Gestion des entites ***************************************************************************************/

    /**
     * @param AgentAccompagnement $accompagnement
     * @return AgentAccompagnement
     */
    public function create(AgentAccompagnement $accompagnement) : AgentAccompagnement
    {
        try {
            $this->getEntityManager()->persist($accompagnement);
            $this->getEntityManager()->flush($accompagnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $accompagnement;
    }

    /**
     * @param AgentAccompagnement $accompagnement
     * @return AgentAccompagnement
     */
    public function update(AgentAccompagnement $accompagnement) : AgentAccompagnement
    {
        try {
            $this->getEntityManager()->flush($accompagnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $accompagnement;
    }

    /**
     * @param AgentAccompagnement $accompagnement
     * @return AgentAccompagnement
     */
    public function restore(AgentAccompagnement $accompagnement)  :AgentAccompagnement
    {
        try {
            $accompagnement->dehistoriser();
            $this->getEntityManager()->flush($accompagnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $accompagnement;
    }

    /**
     * @param AgentAccompagnement $accompagnement
     * @return AgentAccompagnement
     */
    public function historise(AgentAccompagnement $accompagnement) : AgentAccompagnement
    {
        try {
            $accompagnement->historiser();
            $this->getEntityManager()->flush($accompagnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $accompagnement;
    }

    /**
     * @param AgentAccompagnement $accompagnement
     * @return AgentAccompagnement
     */
    public function delete(AgentAccompagnement $accompagnement) : AgentAccompagnement
    {
        try {
            $this->getEntityManager()->remove($accompagnement);
            $this->getEntityManager()->flush($accompagnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $accompagnement;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentAccompagnement::class)->createQueryBuilder('accompagnement')
            ->join('accompagnement.agent', 'agent')->addSelect('agent')
            ->leftjoin('accompagnement.cible', 'cible')->addSelect('cible')
            ->leftjoin('accompagnement.bap', 'bap')->addSelect('bap')
            ->leftjoin('accompagnement.corps', 'corps')->addSelect('corps')
            ->leftjoin('accompagnement.etat', 'etat')->addSelect('etat');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return AgentAccompagnement|null
     */
    public function getAgentAccompagnement(?int $id) : ?AgentAccompagnement
    {
        $qb = $this->createQueryBuilder()->andWhere('accompagnement.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs AgentAccompagnement partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return AgentAccompagnement|null
     */
    public function getRequestedAgentAccompagnement(AbstractActionController $controller, string $param='accompagnement') : ?AgentAccompagnement
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getAgentAccompagnement($id);
        return $result;
    }

    /**
     * @param Agent $agent
     * @return AgentAccompagnement[]
     */
    public function getAgentAccompagnementsByAgent(Agent $agent) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('accompagnement.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('accompagnement.dateDebut','DESC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}