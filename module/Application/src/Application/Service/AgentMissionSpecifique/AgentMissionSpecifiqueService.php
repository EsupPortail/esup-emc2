<?php

namespace Application\Service\AgentMissionSpecifique;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentMissionSpecifique;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use MissionSpecifique\Entity\Db\MissionSpecifique;
use RuntimeException;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;

class AgentMissionSpecifiqueService
{
    use ProvidesObjectManager;
    use StructureServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(AgentMissionSpecifique $agentMissionSpecifique): AgentMissionSpecifique
    {
        $this->getObjectManager()->persist($agentMissionSpecifique);
        $this->getObjectManager()->flush($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    public function update(AgentMissionSpecifique $agentMissionSpecifique): AgentMissionSpecifique
    {
        $this->getObjectManager()->flush($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    public function historise(AgentMissionSpecifique $agentMissionSpecifique): AgentMissionSpecifique
    {
        $agentMissionSpecifique->historiser();
        $this->getObjectManager()->flush($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    public function restore(AgentMissionSpecifique $agentMissionSpecifique): AgentMissionSpecifique
    {
        $agentMissionSpecifique->dehistoriser();
        $this->getObjectManager()->flush($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    public function delete(AgentMissionSpecifique $agentMissionSpecifique): AgentMissionSpecifique
    {
        $this->getObjectManager()->remove($agentMissionSpecifique);
        $this->getObjectManager()->flush($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('agentmission')
            ->join('agentmission.agent', 'agent')->addSelect('agent')
            ->join('agentmission.mission', 'mission')->addSelect('mission')
            ->leftJoin('agentmission.structure', 'structure')->addSelect('structure');
        return $qb;
    }

    public function getAgentMissionSpecifique(?int $id): ?AgentMissionSpecifique
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException("Plusieurs AgentMissionSpecifique partagent le même identifiant [" . $id . "].", 0, $e);
        }
        return $result;
    }

    public function getRequestedAgentMissionSpecifique(AbstractActionController $controller, string $paramName = 'agent-mission-specifique'): ?AgentMissionSpecifique
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getAgentMissionSpecifique($id);
        return $result;
    }

    /**
     * @return AgentMissionSpecifique[]
     */
    public function getAgentMissionsSpecifiquesByAgent(Agent $agent, bool $actif = true): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.agent = :agent')
            ->setParameter('agent', $agent);

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAgentMissionsSpecifiquesByAgents(array $agents, bool $actif = true): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.agent in (:agents)')
            ->setParameter('agents', $agents);

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return AgentMissionSpecifique[]
     */
    public function getAgentMissionsSpecifiquesByMissionSpecifique(MissionSpecifique $mission, bool $actif = true): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.mission = :mission')
            ->setParameter('mission', $mission);

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return AgentMissionSpecifique[]
     */
    public function getAgentMissionsSpecifiquesByStructure(Structure $structure, bool $actif = true): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.structure = :structure')
            ->setParameter('structure', $structure);

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param array $structures
     * @param bool $actif
     * @return array
     */
    public function getAgentMissionsSpecifiquesByStructures(array $structures, bool $actif = true): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmission.structure in (:structures)')
            ->setParameter('structures', $structures);

        if ($actif === true) $qb = AgentMissionSpecifique::decorateWithActif($qb, 'agentmission');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAgentMissionsSpecifiquesWithFiltre(array $params)
    {
        $qb = $this->createQueryBuilder();

        if (isset($params['type']) and $params['type'] !== '') {
            $qb = $qb->join('mission.type', 'type')
                ->andWhere('type.id = :type')->setParameter('type', $params['type']);
        }
        if (isset($params['theme']) and $params['theme'] !== '') {
            $qb = $qb->join('mission.theme', 'theme')
                ->andWhere('theme.id = :theme')->setParameter('theme', $params['theme']);
        }
        if (isset($params['mission']) and $params['mission'] !== '') {
            $qb = $qb
                ->andWhere('mission.id = :mission')->setParameter('mission', $params['mission']);
        }
        if (isset($params['agent-filtre']) and isset($params['agent-filtre']['id']) and $params['agent-filtre']['id'] !== '') {
            $qb = $qb
                ->andWhere('agent.id = :agent')->setParameter('agent', $params['agent-filtre']['id']);
        }
        if (isset($params['structure-filtre']) and isset($params['structure-filtre']['id']) and $params['structure-filtre']['id'] !== '') {
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