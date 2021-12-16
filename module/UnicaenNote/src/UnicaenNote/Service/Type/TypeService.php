<?php

namespace UnicaenNote\Service\Type;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenNote\Entity\Db\Type;
use Zend\Mvc\Controller\AbstractActionController;

class TypeService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Type $type
     * @return Type
     */
    public function create(Type $type) : Type
    {
        try {
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param Type $type
     * @return Type
     */
    public function update(Type $type) : Type
    {
        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param Type $type
     * @return Type
     */
    public function historise(Type $type) : Type
    {
        try {
            $type->historiser();
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param Type $type
     * @return Type
     */
    public function restore(Type $type) : Type
    {
        try {
            $type->dehistoriser();
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param Type $type
     * @return Type
     */
    public function delete(Type $type) : Type
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Type::class)->createQueryBuilder('ntype')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Type[]
     */
    public function getTypes(string $champ='code', string $ordre='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('ntype.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getTypesAsOptions(string $champ='code', string $ordre='ASC')
    {
        $types = $this->getTypes($champ, $ordre);
        $array = [];
        foreach ($types as $type) {
            $array[$type->getId()] = $type->getLibelle();
        }
        return $array;
    }

    /**
     * @param int $id
     * @return Type
     */
    public function getType(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ntype.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Type partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Type
     */
    public function getRequestedType(AbstractActionController $controller, string $param='type')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getType($id);
        return $result;
    }
}