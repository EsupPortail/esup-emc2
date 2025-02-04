<?php

namespace Application\Service\AgentMissionSpecifique;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentMissionSpecifique;
use MissionSpecifique\Entity\Db\MissionSpecifique;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class AgentMissionSpecifiqueService
{
    use EntityManagerAwareTrait;
    use StructureServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function create(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        try {
            $this->getEntityManager()->persist($agentMissionSpecifique);
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function update(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        try {
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function historise(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        try {
            $agentMissionSpecifique->historiser();
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function restore(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        try {
            $agentMissionSpecifique->dehistoriser();
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function delete(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        try {
            $this->getEntityManager()->remove($agentMissionSpecifique);
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
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

    public function getAgentMissionsSpecifiquesByAgents(array $agents,  bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.agent in (:agents)')
            ->setParameter('agents', $agents)
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

    public function getAgentMissionsSpecifiquesWithFiltre(array $params)
    {
        $qb = $this->createQueryBuilder();

        if (isset($params['type']) AND $params['type'] !== '') {
            $qb = $qb->join('mission.type', 'type')
                ->andWhere('type.id = :type')->setParameter('type', $params['type']);
        }
        if (isset($params['theme']) AND $params['theme'] !== '') {
            $qb = $qb->join('mission.theme', 'theme')
                ->andWhere('theme.id = :theme')->setParameter('theme', $params['theme']);
        }
        if (isset($params['mission']) AND $params['mission'] !== '') {
            $qb = $qb
                ->andWhere('mission.id = :mission')->setParameter('mission', $params['mission']);
        }
        if (isset($params['agent-filtre']) AND isset($params['agent-filtre']['id']) AND $params['agent-filtre']['id'] !== '') {
            $qb = $qb
                ->andWhere('agent.id = :agent')->setParameter('agent', $params['agent-filtre']['id']);
        }
        if (isset($params['structure-filtre']) AND isset($params['structure-filtre']['id']) AND $params['structure-filtre']['id'] !== '') {
            $structureIds = [];
            $structure = $this->getStructureService()->getStructure($params['structure-filtre']['id']);
            if ($structure) {
                $structures = $this->getStructureService()->getStructuresFilles($structure, true);
                foreach ($structures as $structure_) {
                    $structureIds[$structure_->getId()] = $structure_->getId();
                }
            }
            $qb = $qb
                ->andWhere('structure.id in (:structureIds)')->setParameter('structureIds', $structureIds);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}