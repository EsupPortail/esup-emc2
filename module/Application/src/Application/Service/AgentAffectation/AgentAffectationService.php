<?php

namespace Application\Service\AgentAffectation;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAffectation;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use Structure\Entity\Db\Structure;
use UnicaenApp\Service\EntityManagerAwareTrait;

class AgentAffectationService {
    use EntityManagerAwareTrait;

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentAffectation::class)->createQueryBuilder('agentaffectation')
            ->join('agentaffectation.agent', 'agent')->addSelect('agent')
            ->join('agentaffectation.structure', 'structure')->addSelect('structure')
            ->andWhere('agentaffectation.deleted_on IS NULL')
        ;
        return $qb;
    }

    /** @return AgentAffectation[] */
    public function getAgentsAffectationsByAgentAndDate(Agent $agent, ?DateTime $date = null) : array
    {
        if ($date === null) $date = new DateTime();
        $qb= $this->createQueryBuilder()
            ->andWhere('agentaffectation.agent = :agent')
            ->setParameter('agent', $agent)
        ;

//        $entityName = 'agentaffectation';
//        $qb = $qb
//            ->andWhere("(" .$entityName . '.dateDebut IS NULL OR ' . $entityName . '.dateDebut <= :date'.")")
////            ->andWhere("agentaffectation.dateFin >= :date")
//            ->setParameter('date', $date)
//        ;

        var_dump($date);
        $result = $qb->getQuery()->getResult();
        $result = array_filter($result, function (AgentAffectation $a) use ($date) {
//            var_dump($a->getDateDebut()->format('H/m/Y'));
            if ($a->getDateDebut() !== null AND $a->getDateDebut() >= $date) return false;
//            var_dump($a->getDateFin()->format('H/m/Y'));
            if ($a->getDateFin() !== null AND $a->getDateFin() <= $date) return false;
            return true;
        });
        return $result;
    }

    /**
     * @param Agent $agent
     * @param bool $actif
     * @param bool $principale
     * @param bool $hierarchique
     * @param bool $fonctionnelle
     * @return AgentAffectation[]
     */
    public function getAgentAffectationsByAgent(Agent $agent, bool $actif = true, bool $principale = false, bool $hierarchique = true, bool $fonctionnelle = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentaffectation.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('agentaffectation.dateDebut', 'DESC')
        ;

        if ($principale) $qb = $qb->andWhere("agentaffectation.principale = 'O'");
        if ($hierarchique) $qb = $qb->andWhere("agentaffectation.hierarchique = 'O'");
        if ($fonctionnelle) $qb = $qb->andWhere("agentaffectation.fonctionnelle = 'O'");
        if ($actif === true) $qb = AgentAffectation::decorateWithActif($qb, 'agentaffectation');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentAffectation[]|null */
    public function getAgentAffectationHierarchiquePrincipaleByAgent(Agent $agent) : ?array
    {
        $result = $this->getAgentAffectationsByAgent($agent, true, true,true, false);
        $nb = count($result);
//        if ( $nb > 1) throw new RuntimeException("Plusieurs affections hiérarchique principale pour l'agent [".$agent->getDenomination()."]");
        if ($nb === 0 ) return null;
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $actif
     * @return array
     */
    public function getAgentAffectationsByStructure(Structure $structure, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentaffectation.structure = :structure')
            ->setParameter('structure', $structure)
        ;

        if ($actif === true) $qb = AgentAffectation::decorateWithActif($qb, 'agentaffectation');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}