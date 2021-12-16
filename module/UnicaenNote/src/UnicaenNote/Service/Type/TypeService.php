<?php

namespace UnicaenNote\Service\Type;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenNote\Entity\Db\Type;
use Application\Service\GestionEntiteHistorisationTrait;
use Zend\Mvc\Controller\AbstractActionController;

class TypeService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Type $type
     * @return Type
     */
    public function create(Type $type)
    {
        $this->createFromTrait($type);
        return $type;
    }

    /**
     * @param Type $type
     * @return Type
     */
    public function update(Type $type)
    {
        $this->updateFromTrait($type);
        return $type;
    }

    /**
     * @param Type $type
     * @return Type
     */
    public function historise(Type $type)
    {
        $this->historiserFromTrait($type);
        return $type;
    }

    /**
     * @param Type $type
     * @return Type
     */
    public function restore(Type $type)
    {
        $this->restoreFromTrait($type);
        return $type;
    }

    /**
     * @param Type $type
     * @return Type
     */
    public function delete(Type $type)
    {
        $this->deleteFromTrait($type);
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