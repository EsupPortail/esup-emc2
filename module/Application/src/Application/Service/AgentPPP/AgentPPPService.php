<?php

namespace Application\Service\AgentPPP;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentPPP;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class AgentPPPService {
    use GestionEntiteHistorisationTrait;

    /** Gestion des entites ***************************************************************************************/

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function create(AgentPPP $ppp) : AgentPPP
    {
        $this->createFromTrait($ppp);
        return $ppp;
    }

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function update(AgentPPP $ppp) : AgentPPP
    {
        $this->updateFromTrait($ppp);
        return $ppp;
    }

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function restore(AgentPPP $ppp)  :AgentPPP
    {
        $this->restoreFromTrait($ppp);
        return $ppp;
    }

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function historise(AgentPPP $ppp) : AgentPPP
    {
        $this->historiserFromTrait($ppp);
        return $ppp;
    }

    /**
     * @param AgentPPP $ppp
     * @return AgentPPP
     */
    public function delete(AgentPPP $ppp) : AgentPPP
    {
        $this->deleteFromTrait($ppp);
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