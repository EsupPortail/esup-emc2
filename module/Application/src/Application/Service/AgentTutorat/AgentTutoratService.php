<?php

namespace Application\Service\AgentTutorat;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentTutorat;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class AgentTutoratService {
    use EntityManagerAwareTrait;

    /** Gestion des entites ***************************************************************************************/

    /**
     * @param AgentTutorat $tutorat
     * @return AgentTutorat
     */
    public function create(AgentTutorat $tutorat) : AgentTutorat
    {
        try {
            $this->getEntityManager()->persist($tutorat);
            $this->getEntityManager()->flush($tutorat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $tutorat;
    }

    /**
     * @param AgentTutorat $tutorat
     * @return AgentTutorat
     */
    public function update(AgentTutorat $tutorat) : AgentTutorat
    {
        try {
            $this->getEntityManager()->flush($tutorat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $tutorat;
    }

    /**
     * @param AgentTutorat $tutorat
     * @return AgentTutorat
     */
    public function historise(AgentTutorat $tutorat) : AgentTutorat
    {
        try {
            $tutorat->historiser();
            $this->getEntityManager()->flush($tutorat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $tutorat;
    }

    /**
     * @param AgentTutorat $tutorat
     * @return AgentTutorat
     */
    public function restore(AgentTutorat $tutorat)  :AgentTutorat
    {
        try {
            $tutorat->dehistoriser();
            $this->getEntityManager()->flush($tutorat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $tutorat;
    }

    /**
     * @param AgentTutorat $tutorat
     * @return AgentTutorat
     */
    public function delete(AgentTutorat $tutorat) : AgentTutorat
    {
        try {
            $this->getEntityManager()->remove($tutorat);
            $this->getEntityManager()->flush($tutorat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $tutorat;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentTutorat::class)->createQueryBuilder('tutorat')
            ->join('tutorat.agent', 'agent')->addSelect('agent')
            ->leftjoin('tutorat.cible', 'cible')->addSelect('cible')
            ->leftjoin('tutorat.metier', 'metier')->addSelect('metier')
            ->leftjoin('tutorat.etat', 'etat')->addSelect('etat');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return AgentTutorat|null
     */
    public function getAgentTutorat(?int $id) : ?AgentTutorat
    {
        $qb = $this->createQueryBuilder()->andWhere('tutorat.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs AgentTutorat partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return AgentTutorat|null
     */
    public function getRequestedAgentTutorat(AbstractActionController $controller, string $param='tutorat') : ?AgentTutorat
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getAgentTutorat($id);
        return $result;
    }

    /**
     * @param Agent $agent
     * @return AgentTutorat[]
     */
    public function getAgentTutoratsByAgent(Agent $agent) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('tutorat.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('tutorat.dateDebut','DESC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}