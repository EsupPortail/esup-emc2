<?php

namespace Application\Service\AgentGrade;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentGrade;
use Carriere\Entity\Db\Corps;
use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\Grade;
use Doctrine\ORM\QueryBuilder;
use Structure\Entity\Db\Structure;
use UnicaenApp\Service\EntityManagerAwareTrait;

class AgentGradeService {
    use EntityManagerAwareTrait;

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentGrade::class)->createQueryBuilder('agentgrade')
            ->join('agentgrade.agent', 'agent')->addSelect('agent')
            ->join('agentgrade.structure', 'structure')->addSelect('structure')
            ->join('agentgrade.grade', 'grade')->addSelect('grade')
            ->join('agentgrade.corps', 'corps')->addSelect('corps')
            ->join('agentgrade.bap', 'correspondance')->addSelect('correspondance')
            ->andWhere('agentgrade.deleted_on IS NULL')
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
        ;

        if ($actif === true) $qb = AgentGrade::decorateWithActif($qb, 'agentgrade');

        $result = $qb->getQuery()->getResult();
        return $result;
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
            ->andWhere('agentgrade.bap = :correspondance')
            ->setParameter('correspondance', $correspondance)
        ;

        if ($actif === true) $qb = AgentGrade::decorateWithActif($qb, 'agentgrade');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}