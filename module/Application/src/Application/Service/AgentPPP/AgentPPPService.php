<?php

namespace Application\Service\AgentPPP;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentPPP;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class AgentPPPService {
    use EntityManagerAwareTrait;

    /** Gestion des entites ***************************************************************************************/

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function create(AgentPPP $ppp) : AgentPPP
    {
        try {
            $this->getEntityManager()->persist($ppp);
            $this->getEntityManager()->flush($ppp);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ppp;
    }

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function update(AgentPPP $ppp) : AgentPPP
    {
        try {
            $this->getEntityManager()->flush($ppp);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ppp;
    }

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function restore(AgentPPP $ppp)  :AgentPPP
    {
        try {
            $ppp->historiser();
            $this->getEntityManager()->flush($ppp);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ppp;
    }

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function historise(AgentPPP $ppp) : AgentPPP
    {
        try {
            $ppp->dehistoriser();
            $this->getEntityManager()->flush($ppp);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ppp;
    }

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function delete(AgentPPP $ppp) : AgentPPP
    {
        try {
            $this->getEntityManager()->remove($ppp);
            $this->getEntityManager()->flush($ppp);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $ppp;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {

        $qb = $this->getEntityManager()->getRepository(AgentPPP::class)->createQueryBuilder('ppp')
            ->join('ppp.agent', 'agent')->addSelect('agent')
            ->leftjoin('ppp.etat', 'etat')->addSelect('etat');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return AgentPPP|null
     */
    public function getAgentPPP(?int $id) : ?AgentPPP
    {
        $qb = $this->createQueryBuilder()->andWhere('ppp.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs AgentPPP partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return AgentPPP|null
     */
    public function getRequestedAgentPPP(AbstractActionController $controller, string $param='ppp') : ?AgentPPP
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getAgentPPP($id);
        return $result;
    }

    /**
     * @param Agent $agent
     * @return AgentPPP[]
     */
    public function getAgentPPPsByAgent(Agent $agent) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ppp.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('ppp.dateDebut','DESC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}