<?php

namespace Application\Service\AgentMissionSpecifique;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\Structure;
use Application\Service\Structure\StructureServiceAwareTrait;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Application\Service\GestionEntiteHistorisationTrait;
use Zend\Mvc\Controller\AbstractActionController;

class AgentMissionSpecifiqueService
{
    use GestionEntiteHistorisationTrait;
    use StructureServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function create(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->createFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function update(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->updateFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function historise(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->historiserFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function restore(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->restoreFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function delete(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->deleteFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('agentmission')
            ->join('agentmission.agent', 'agent')->addSelect('agent')
            ->join('agentmission.mission', 'mission')->addSelect('mission')
            ->leftJoin('agentmission.structure', 'structure')->addSelect('structure')
        ;
        return $qb;
    }

    /**
     * @param integer $id
     * @return AgentMissionSpecifique|null
     */
    public function getAgentMissionSpecifique(int $id) : ?AgentMissionSpecifique
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException("Plusieurs AgentMissionSpecifique partagent le même identifiant [". $id ."].", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return AgentMissionSpecifique|null
     */
    public function getRequestedAgentMissionSpecifique(AbstractActionController $controller, string $paramName = 'agent-mission-specifique') : ?AgentMissionSpecifique
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getAgentMissionSpecifique($id);
        return $result;
    }

    /**
     * @param Agent $agent
     * @param bool $actif
     * @return AgentMissionSpecifique[]
     */
    public function getAgentMissionsSpecifiquesByAgent(Agent $agent, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.agent = :agent')
            ->setParameter('agent', $agent)
        ;

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param MissionSpecifique $mission
     * @param bool $actif
     * @return AgentMissionSpecifique[]
     */
    public function getAgentMissionsSpecifiquesByMissionSpecifique(MissionSpecifique $mission, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.mission = :mission')
            ->setParameter('mission', $mission)
        ;

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $actif
     * @return AgentMissionSpecifique[]
     */
    public function getAgentMissionsSpecifiquesByStructure(Structure $structure, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.structure = :structure')
            ->setParameter('structure', $structure)
        ;

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param array $structures
     * @param bool $actif
     * @return array
     */
    public function getAgentMissionsSpecifiquesByStructures(array $structures, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.structure in (:structures)')
            ->setParameter('structures', $structures)
        ;

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent|null $agent
     * @param MissionSpecifique|null $mission
     * @param Structure|null $structure
     * @param bool $actif
     * @return array
     */
    public function getAgentMissionsSpecifiquesByAgentAndMissionAndStructure(?Agent $agent, ?MissionSpecifique  $mission, ?Structure $structure, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder();

        if ($agent) {
            $qb = $qb->andWhere('agentmission.agent = :agent')
                ->setParameter('agent', $agent);
        }

        if ($mission) {
            $qb = $qb->andWhere('agentmission.mission = :mission')
                ->setParameter('mission', $mission);
        }

        if ($structure) {
            $structures = $this->getStructureService()->getStructuresFilles($structure);
            $structures[] = $structure;

            $qb = $qb
                ->andWhere('agentmission.structure in (:structures)')
                ->setParameter('structures', $structures)
            ;
        }

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}