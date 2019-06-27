<?php

namespace Application\Service\Structure;

use Application\Entity\Db\Structure;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Entity\Db\User;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class StructureService
{
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @return Structure[]
     */
    public function getStructures($ouverte = true)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->orderBy('structure.code');
        if ($ouverte) $qb = $qb->andWhere("structure.histo = 'O'");
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param integer $id
     * @return Structure
     */
    public function getStructure($id)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->andWhere('structure.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Structure partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Structure
     */
    public function getRequestedStructure($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $structure = $this->getStructure($id);
        return $structure;
    }

    /**
     * @return array
     */
    public function getStructuresAsOptions($ouverte = true)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->orderBy('structure.libelleLong')
        ;
        if ($ouverte) $qb = $qb->andWhere("structure.histo = 'O'");

        $result = $qb->getQuery()->getResult();

        $options = [];
        /** @var Structure $item */
        foreach ($result as $item) {
            if ($item->getId() !== null) $options[$item->getId()] = $item->getLibelleLong();
        }
        return $options;
    }

    /**
     * @param Structure $structure
     * @param User $gestionnaire
     * @return Structure
     */
    public function addGestionnaire($structure, $gestionnaire)
    {
        $structure->addGestionnaire($gestionnaire);
        try {
            $this->getEntityManager()->flush($structure);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'inscription en base.", $e);
        }
        return $structure;
    }

    /**
     * @param Structure $structure
     * @param User $gestionnaire
     * @return Structure
     */
    public function removeGestionnaire($structure, $gestionnaire)
    {
        $structure->removeGestionnaire($gestionnaire);
        try {
            $this->getEntityManager()->flush($structure);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'inscription en base.", $e);
        }
        return $structure;
    }

    /**
     * @param Structure
     * @return Structure
     */
    public function update($structure)
    {
        try {
            $this->getEntityManager()->flush($structure);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'inscription en base.", $e);
        }
        return $structure;
    }
}