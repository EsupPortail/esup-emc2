<?php

namespace Structure\Service\StructureAgentForce;

use Application\Entity\Db\Agent;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureAgentForce;
use UnicaenApp\Exception\RuntimeException;

class StructureAgentForceService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(StructureAgentForce $structureAgentForce): StructureAgentForce
    {
        $this->getObjectManager()->persist($structureAgentForce);
        $this->getObjectManager()->flush($structureAgentForce);
        return $structureAgentForce;
    }

    public function upgrade(StructureAgentForce $structureAgentForce): StructureAgentForce
    {
        $this->getObjectManager()->flush($structureAgentForce);
        return $structureAgentForce;
    }

    public function historise(StructureAgentForce $structureAgentForce): StructureAgentForce
    {
        $structureAgentForce->historiser();
        $this->getObjectManager()->flush($structureAgentForce);
        return $structureAgentForce;
    }

    public function restore(StructureAgentForce $structureAgentForce): StructureAgentForce
    {
        $structureAgentForce->dehistoriser();
        $this->getObjectManager()->flush($structureAgentForce);
        return $structureAgentForce;
    }

    public function delete(StructureAgentForce $structureAgentForce): StructureAgentForce
    {
        $this->getObjectManager()->remove($structureAgentForce);
        $this->getObjectManager()->flush($structureAgentForce);
        return $structureAgentForce;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(StructureAgentForce::class)->createQueryBuilder('force')
            ->addSelect('agent')->join('force.agent', 'agent')
            ->addSelect('structure')->join('force.structure', 'structure');
        return $qb;
    }

    /**
     * @param int $id
     * @return StructureAgentForce|null
     */
    public function getStructureAgentForce(int $id): ?StructureAgentForce
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('force.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs StructureAgentForce partagent le même id [" . $id . "]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return StructureAgentForce|null
     */
    public function getRequestedStructureAgentForce(AbstractActionController $controller, string $param = "structure-agent-force"): ?StructureAgentForce
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getStructureAgentForce($id);
        return $result;
    }

    public function getStructureAgentForceByStructureAndAgent(?Structure $structure, ?Agent $agent): ?StructureAgentForce
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('force.agent = :agent')
            ->andWhere('force.structure = :structure')
            ->andWhere('force.histoDestruction IS NULL')
            ->setParameter('agent', $agent)
            ->setParameter('structure', $structure);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs StructureAgentForce partagent le même agent [" . $agent->getId() . "] et la même structure [" . $structure->getId() . "].");
        }
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @return StructureAgentForce[]
     */
    public function getStructureAgentsForcesByStructures(array $structures): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('force.structure in (:structures)')->setParameter('structures', $structures)
            ->andWhere('force.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}