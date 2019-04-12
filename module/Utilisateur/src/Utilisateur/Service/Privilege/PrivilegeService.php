<?php

namespace Utilisateur\Service\Privilege;

use UnicaenAuth\Entity\Db\CategoriePrivilege;
use Utilisateur\Entity\Db\Privilege;
use Utilisateur\Entity\Db\Role;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class PrivilegeService {
    use EntityManagerAwareTrait;

    /**
     * @return Privilege[]
     */
    public function getPrivileges()
    {
        $qb = $this->getEntityManager()->getRepository(Privilege::class)->createQueryBuilder('privilege')
            ->orderBy('privilege.categorie', 'ASC')
            ->addOrderBy('privilege.ordre', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getPrivilegesWithCategories()
    {
        $qb = $this->getEntityManager()->getRepository(CategoriePrivilege::class)->createQueryBuilder('categorie')
            ->orderBy('categorie.ordre', 'ASC')
        ;
        $categories = $qb->getQuery()->getResult();

        $privileges = [];

        /** @var CategoriePrivilege $categorie */
        foreach ($categories as $categorie) {
            $qb = $this->getEntityManager()->getRepository(Privilege::class)->createQueryBuilder('privilege')
                ->andWhere('privilege.categorie = :id')
                ->setParameter('id', $categorie->getId())
                ->orderBy('privilege.ordre', 'ASC')
            ;

            $result = $qb->getQuery()->getResult();
            if ($result && count($result) > 0) {
                $privileges[$categorie->getLibelle()] = $result;
            }
        }

        return $privileges;
    }

    /**
     * @param int $privilegeId
     * @return Privilege
     */
    public function getPrivilege($privilegeId)
    {
        $qb = $this->getEntityManager()->getRepository(Privilege::class)->createQueryBuilder('privilege')
            ->andWhere('privilege.id = :id')
            ->setParameter('id', $privilegeId)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Privilege partagent le même identifiant [".$privilegeId."]",$e);
        }
        return $result;
    }

    /**
     * @param Role $role
     * @param Privilege $privilege
     * @return boolean
     */
    public function toggle($role, $privilege) {
        $value = null;
        if ($privilege->hasRole($role)) {
            $privilege->removeRole($role);
            $value = false;
        } else {
            $privilege->addRole($role);
            $value = true;
        }
        try {
            $this->getEntityManager()->flush($privilege);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors du changement du privilège.", $e);
        }
        return $value;
    }

    /**
     * @param $roleId
     * @return Role
     */
    public function getRole($roleId)
    {
        $qb = $this->getEntityManager()->getRepository(Role::class)->createQueryBuilder('role')
            ->andWhere('role.id = :id')
            ->setParameter('id', $roleId)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Role partagent le même identifiant [".$roleId."]",$e);
        }
        return $result;
        
    }
}

