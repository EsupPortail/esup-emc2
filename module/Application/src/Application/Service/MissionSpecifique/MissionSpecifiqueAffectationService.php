<?php

namespace Application\Service\MissionSpecifique;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\Structure;
use Application\Service\GestionEntiteHistorisationTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class MissionSpecifiqueAffectationService {
//    use DateTimeAwareTrait;
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;
    use StructureServiceAwareTrait;

    /** ENTITY MANAGEMENT *********************************************************************************************/

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function create($affectation)
    {
        $this->createFromTrait($affectation);
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function update($affectation)
    {
        $this->updateFromTrait($affectation);
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function historise($affectation)
    {
        $this->historiserFromTrait($affectation);
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function restore($affectation)
    {
        $this->restoreFromTrait($affectation);
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function delete($affectation)
    {
        $this->deleteFromTrait($affectation);
        return $affectation;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() {

        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('affectation')
            ->addSelect('agent')->leftJoin('affectation.agent', 'agent')
            ->addSelect('mission')->leftJoin('affectation.mission', 'mission')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
        ;

        return $qb;
    }

    /**
     * @param bool $historise
     * @return AgentMissionSpecifique::class,
     */
    public function getMissionsSpecifiquesAffectations($historise = true)
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('agent.nomUsuel, agent.prenom, mission.libelle, structure.libelleLong', 'ASC')
        ;

        if (! $historise) $qb = $qb->andWhere('affectation.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent $agent
     * @param MissionSpecifique $mission
     * @param Structure $structure
     * @return AgentMissionSpecifique[]
     */
    public function getAffectations($agent, $mission, $structure)
    {
        $structures =[];
        if ($structure !== null) {
            $structures = $this->getStructureService()->getStructuresFilles($structure);
            $structures[] = $structure;
        }

        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('affectation')
            ->addSelect('agent')->leftJoin('affectation.agent', 'agent')
            ->addSelect('mission')->leftJoin('affectation.mission', 'mission')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->orderBy('agent.nomUsuel, agent.prenom, mission.libelle, structure.libelleLong', 'ASC')
        ;

        if ($agent !== null) {
            $qb = $qb->andWhere('agent.id = :agentId')
                ->setParameter('agentId', $agent->getId())
            ;
        }
        if ($mission !== null) {
            $qb = $qb->andWhere('mission.id = :missionId')
                ->setParameter('missionId', $mission->getId())
            ;
        }
        if (!empty($structures)) {
            $qb = $qb->andWhere('affectation.structure in (:structures)')
                ->setParameter('structures', $structures)
            ;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return AgentMissionSpecifique
     */
    public function getAffectation($id)
    {
        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('affectation')
            ->addSelect('agent')->leftJoin('affectation.agent', 'agent')
            ->addSelect('mission')->leftJoin('affectation.mission', 'mission')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->andWhere('affectation.id = :id')
            ->setParameter('id', $id)
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs AgentMissionSpecifique partagent le même id [".$id."]", $e);
        }
        return $result;

    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return AgentMissionSpecifique
     */
    public function getRequestedAffectation($controller, $paramName='affectation')
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getAffectation($id);
    }



    /**
     * @param Structure $structure
     * @param bool $sousstructure
     * @return AgentMissionSpecifique[]
     */
    public function getMissionsSpecifiquesByStructure(Structure $structure, $sousstructure = false)
    {
        $today = $this->getDateTime();

        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('mission')
            ->addSelect('structure')->join('mission.structure', 'structure')
            ->andWhere('mission.structure = :structure OR structure.parent = :structure')
            ->andWhere('mission.dateFin >= :today OR mission.dateFin IS NULL')
            ->setParameter('structure', $structure)
            ->setParameter('today', $today);
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @param boolean $active
     * @return AgentMissionSpecifique[]
     */
    public function getMissionsSpecifiquesByStructures($structures, $active = true)
    {
        $date = $this->getDateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere('affectation.structure IN (:structures)')
            ->setParameter('structures', $structures)
            ->orderBy('agent.nomUsuel, agent.prenom, structure.libelleLong, mission.libelle', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}