<?php

namespace UnicaenUtilisateur\Service\Role;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class RoleService
{
    use EntityManagerAwareTrait;

    private $roleEntityClass;

    public function setRoleEntityClass($roleEntityClass)
    {
        $this->roleEntityClass = $roleEntityClass;
    }


    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->roleEntityClass;
    }

    /**
     * @return Role[]
     */
    public function getRoles($champ = 'roleId', $ordre = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository($this->roleEntityClass)->createQueryBuilder('role')
            ->orderBy('role.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return Role[]
     */
    public function getRole($id)
    {
        $qb = $this->getEntityManager()->getRepository($this->roleEntityClass)->createQueryBuilder("role")
            ->andWhere("role.id = :id")
            ->setParameter("id", $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs rôles partagent l'identifiant : ".$id);
        }
        return $result;
    }

    public function getRoleByCode($code)
    {
        $qb = $this->getEntityManager()->getRepository($this->roleEntityClass)->createQueryBuilder('role')
            ->andWhere('role.roleId = :code')
            ->setParameter('code', $code)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs rôles partagent le code : ".$code);
        }
        return $result;
    }
    public function getRolesAsOptions()
    {
        $roles = $this->getRoles();
        $array = [];
        foreach ($roles as $role) {
            $array[$role->getId()] = $role->getRoleId();
        }
        return $array;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Role
     */
    public function getRequestedRole($controller, $paramName = 'role')
    {
        $id = $controller->params()->fromRoute($paramName);
        $role = $this->getRole($id);
        return $role;
    }

    /**
     * @param Role $role
     * @return Role
     */
    public function create($role)
    {
        try {
            $this->getEntityManager()->persist($role);
            $this->getEntityManager()->flush($role);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'enregistrement en DB.", $e);
        }
        return $role;
    }

    /**
     * @param Role $role
     * @return Role
     */
    public function update($role)
    {
        try {
            $this->getEntityManager()->flush($role);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'enregistrement en DB.", $e);
        }
        return $role;
    }

    /**
     * @param Role $role
     * @return Role
     */
    public function delete($role)
    {
        try {
            $this->getEntityManager()->remove($role);
            $this->getEntityManager()->flush($role);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'enregistrement en DB.", $e);
        }
        return $role;
    }

}