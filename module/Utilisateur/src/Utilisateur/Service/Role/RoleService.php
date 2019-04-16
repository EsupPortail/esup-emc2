<?php

namespace Utilisateur\Service\Role;

use Utilisateur\Entity\Db\Role;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class RoleService
    // TODO extends \UnicaenAuth\Service\RoleService
{
    use EntityManagerAwareTrait;
    /**
     * @return string
     */
    public function getEntityClass()
    {
        return Role::class;
    }

    /**
     * @return Role[]
     */
    public function getRoles()
    {
        $roles = $this->getEntityManager()->getRepository(Role::class)->findAll();
        return $roles;
    }

    /**
     * @param int $id
     * @return Role[]
     */
    public function getRole($id)
    {
        $qb = $this->getEntityManager()->getRepository(Role::class)->createQueryBuilder("role")
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
        $qb = $this->getEntityManager()->getRepository(Role::class)->createQueryBuilder('role')
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


}