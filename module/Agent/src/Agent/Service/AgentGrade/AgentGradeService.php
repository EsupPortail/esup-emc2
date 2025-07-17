<?php

namespace Agent\Service\AgentGrade;

use Application\Entity\Db\Agent;
use Agent\Entity\Db\AgentGrade;
use Carriere\Entity\Db\Corps;
use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\EmploiType;
use Carriere\Entity\Db\Grade;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Structure\Entity\Db\Structure;

class AgentGradeService {
    use ProvidesObjectManager;

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentGrade::class)->createQueryBuilder('agentgrade')
            ->join('agentgrade.agent', 'agent')->addSelect('agent')
            ->join('agentgrade.structure', 'structure')->addSelect('structure')
            ->leftjoin('agentgrade.grade', 'grade')->addSelect('grade')
            ->leftjoin('agentgrade.corps', 'corps')->addSelect('corps')
            ->leftjoin('agentgrade.correspondance', 'correspondance')->addSelect('correspondance')
            ->andWhere('agentgrade.deletedOn IS NULL')
        ;
        return $qb;
    }

    /**
     * @param Agent $agent
     * @param bool $actif
     * @return array
     */
    public function getAgentGradesByAgent(Agent $agent, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentgrade.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('agentgrade.dateDebut', 'DESC')
        ;

        if ($actif === true) $qb = AgentGrade::decorateWithActif($qb, 'agentgrade');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAgentGradesByAgents(array $agents, ?DateTime $date = null) : array
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->createQueryBuilder();
        $qb = $qb   ->andWhere('agentgrade.deletedOn IS NULL')
        ;
        $qb = $qb
            ->andWhere('agentgrade.dateDebut IS NULL OR agentgrade.dateDebut <= :date')
            ->andWhere('agentgrade.dateFin IS NULL OR agentgrade.dateFin >= :date')
            ->setParameter('date', $date)
        ;
        $qb = $qb->andWhere('agentgrade.agent in (:agents)')->setParameter('agents', $agents);
        $qb = $qb->orderBy('grade.libelleLong', 'ASC');

        $result = $qb->getQuery()->getResult();

        /** @var AgentGrade $agentGrade */
        $grades = [];
        foreach ($result as $agentGrade) {
            $agent = $agentGrade->getAgent();
            $grades[$agent->getId()][] = $agentGrade;
        }
        return $grades;
    }

    /**
     * @param Structure $structure
     * @param bool $actif
     * @return array
     */
    public function getAgentGradesByStructure(Structure $structure, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentgrade.structure = :structure')
            ->setParameter('structure', $structure)
        ;

        if ($actif === true) $qb = AgentGrade::decorateWithActif($qb, 'agentgrade');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Grade $grade
     * @param bool $actif
     * @return array
     */
    public function getAgentGradesByGrade(Grade $grade, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentgrade.grade = :grade')
            ->setParameter('grade', $grade)
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')
        ;

        if ($actif === true) $qb = AgentGrade::decorateWithActif($qb, 'agentgrade');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Corps $corps
     * @param bool $actif
     * @return array
     */
    public function getAgentGradesByCorps(Corps $corps, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentgrade.corps = :corps')
            ->setParameter('corps', $corps)
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')
        ;

        if ($actif === true) $qb = AgentGrade::decorateWithActif($qb, 'agentgrade');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Correspondance $correspondance
     * @param bool $actif
     * @return array
     */
    public function getAgentGradesByCorrespondance(Correspondance $correspondance, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentgrade.correspondance = :correspondance')
            ->setParameter('correspondance', $correspondance)
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')
        ;

        if ($actif === true) $qb = AgentGrade::decorateWithActif($qb, 'agentgrade');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param EmploiType $emploiType
     * @param bool $actif
     * @return array
     */
    public function getAgentGradesByEmploiType(EmploiType $emploiType, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentgrade.emploiType = :emploitype')
            ->setParameter('emploitype', $emploiType)
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')
        ;

        if ($actif === true) $qb = AgentGrade::decorateWithActif($qb, 'agentgrade');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}