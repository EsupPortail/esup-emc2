<?php

namespace Agent\Service\AgentAffectation;

use Application\Entity\Db\Agent;
use Agent\Entity\Db\AgentAffectation;
use DateTime;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use RuntimeException;
use Structure\Entity\Db\Structure;

class AgentAffectationService {
    use ProvidesObjectManager;

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentAffectation::class)->createQueryBuilder('agentaffectation')
            ->join('agentaffectation.agent', 'agent')->addSelect('agent')
            ->join('agentaffectation.structure', 'structure')->addSelect('structure')
            ->andWhere('agentaffectation.deletedOn IS NULL')
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

    /** @return AgentAffectation[] */
    public function getAgentAffectationsByAgent(Agent $agent, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentaffectation.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('agentaffectation.dateDebut', 'DESC')
        ;

        if ($actif === true) $qb = AgentAffectation::decorateWithActif($qb, 'agentaffectation');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentAffectation[]|null */
    public function getAgentAffectationHierarchiquePrincipaleByAgent(Agent $agent) : ?array
    {
        $result = $this->getAgentAffectationsByAgent($agent);
        $result = array_filter($result, function (AgentAffectation $a) { return $a->isPrincipale() && $a->isHierarchique();});
        $nb = count($result);
        //        if ($nb > 1) throw new RuntimeException("Plusieurs affections hiérarchiques principales pour l'agent [".$agent->getDenomination()."]");
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

    public function getAgentsAffectationsByAgents(array $agents, ?DateTime $date = null) : array
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->createQueryBuilder();
        $qb = $qb   ->andWhere('agentaffectation.deletedOn IS NULL')
        ;
        $qb = $qb
            ->andWhere('agentaffectation.dateDebut IS NULL OR agentaffectation.dateDebut <= :date')
            ->andWhere('agentaffectation.dateFin IS NULL OR agentaffectation.dateFin >= :date')
            ->setParameter('date', $date)
        ;
        $qb = $qb->andWhere('agentaffectation.agent in (:agents)')->setParameter('agents', $agents);
        $qb = $qb->orderBy('structure.libelleCourt', 'ASC');

        $result = $qb->getQuery()->getResult();

        /** @var AgentAffectation $agentAffectation */
        $affectations = [];
        foreach ($result as $agentAffectation) {
            $agent = $agentAffectation->getAgent();
            $affectations[$agent->getId()][] = $agentAffectation;
        }
        return $affectations;
    }

    public function hasAffectation(Agent $agent, DateTime $dateDebut, DateTime $dateFin) : bool
    {
        $params = ['agentId' => $agent->getId(), 'dateDebut' => $dateDebut->format('Y-m-d'), 'dateFin' => $dateFin->format('Y-m-d')];
        $sql = <<<EOS
select DISTINCT a.c_individu as c_individu
from agent a
join agent_carriere_affectation aca on a.c_individu = aca.agent_id
where
        a.c_individu = :agentId
    and aca.deleted_on IS NULL
    and tsrange(aca.date_debut, aca.date_fin) && tsrange(:dateDebut, :dateFin)
EOS;

        try {
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, $params, ['structures' => ArrayParameterType::INTEGER]);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("[DRV] Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
            }
        } catch (DBA_Exception $e) {
            $complement = '';
            if ($e->getPrevious() AND str_contains($e->getPrevious()->getMessage(), "range lower bound must be less than or equal to range upper bound")) {
                $complement = "Une affectation est mal saisie dans les données sources (date de fin avant date de début).";
            }
            throw new RuntimeException("[DBA] Un problème est survenue lors du calcul pour l'agent [".$agent->getId()."] " . $agent->getDenomination(true) . ".<br>" . $complement, 0, $e);
        }
        return !empty($tmp);
    }
}