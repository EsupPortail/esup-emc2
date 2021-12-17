<?php

namespace Application\Service\StructureAgentForce;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Structure;
use Application\Entity\Db\StructureAgentForce;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class StructureAgentForceService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param StructureAgentForce $structureAgentForce
     * @return StructureAgentForce
     */
    public function create(StructureAgentForce $structureAgentForce) : StructureAgentForce
    {
        try {
            $this->getEntityManager()->persist($structureAgentForce);
            $this->getEntityManager()->flush($structureAgentForce);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $structureAgentForce;
    }

    /**
     * @param StructureAgentForce $structureAgentForce
     * @return StructureAgentForce
     */
    public function upgrade(StructureAgentForce $structureAgentForce) : StructureAgentForce
    {
        try {
            $this->getEntityManager()->flush($structureAgentForce);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $structureAgentForce;
    }

    /**
     * @param StructureAgentForce $structureAgentForce
     * @return StructureAgentForce
     */
    public function historise(StructureAgentForce $structureAgentForce) : StructureAgentForce
    {
        try {
            $structureAgentForce->historiser();
            $this->getEntityManager()->flush($structureAgentForce);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $structureAgentForce;
    }

    /**
     * @param StructureAgentForce $structureAgentForce
     * @return StructureAgentForce
     */
    public function restore(StructureAgentForce $structureAgentForce) : StructureAgentForce
    {
        try {
            $structureAgentForce->dehistoriser();
            $this->getEntityManager()->flush($structureAgentForce);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $structureAgentForce;
    }

    /**
     * @param StructureAgentForce $structureAgentForce
     * @return StructureAgentForce
     */
    public function delete(StructureAgentForce $structureAgentForce) : StructureAgentForce
    {
        try {
            $this->getEntityManager()->remove($structureAgentForce);
            $this->getEntityManager()->flush($structureAgentForce);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $structureAgentForce;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(StructureAgentForce::class)->createQueryBuilder('force')
            ->addSelect('agent')->join('force.agent', 'agent')
            ->addSelect('structure')->join('force.structure', 'structure')
        ;
        return $qb;
    }

    /**
     * @param int $id
     * @return StructureAgentForce|null
     */
    public function getStructureAgentForce(int $id) : ?StructureAgentForce
    {
       $qb = $this->createQueryBuilder()
            ->andWhere('force.id = :id')
            ->setParameter('id', $id)
       ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs StructureAgentForce partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return StructureAgentForce|null
     */
    public function getRequestedStructureAgentForce(AbstractActionController $controller, string $param="structure-agent-force") : ?StructureAgentForce
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getStructureAgentForce($id);
        return $result;
    }

    /**
     * @param Structure|null $structure
     * @param Agent|null $agent
     * @return StructureAgentForce
     */
    public function getStructureAgentForceByStructureAndAgent(?Structure $structure, ?Agent $agent) : ?StructureAgentForce
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('force.agent = :agent')
            ->andWhere('force.structure = :structure')
            ->andWhere('force.histoDestruction IS NULL')
            ->setParameter('agent', $agent)
            ->setParameter('structure', $structure)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs StructureAgentForce partagent le même agent [".$agent->getId()."] et la même structure [".$structure->getId()."].");
        }
        return $result;
    }
}