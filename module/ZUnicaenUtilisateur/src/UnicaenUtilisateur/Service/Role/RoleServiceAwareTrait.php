<?php

namespace UnicaenUtilisateur\Service\Role;

trait RoleServiceAwareTrait
{
    /**
     * @var RoleService
     */
    private $serviceRole;

    /**
     * @param RoleService $serviceRole
     * @return self
     */
    public function setRoleService( RoleService $serviceRole )
    {
        $this->serviceRole = $serviceRole;
        return $this;
    }

    /**
     * @return RoleService
     */
    public function getRoleService()
    {
        return $this->serviceRole;
    }
}