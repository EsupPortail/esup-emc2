<?php

namespace Application\Service\Role;

use Application\Entity\Db\Role;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * @author Jean-Philippe Metivier <jean-philippe.metivier at unicaen.fr>
 */
class RoleService
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
}