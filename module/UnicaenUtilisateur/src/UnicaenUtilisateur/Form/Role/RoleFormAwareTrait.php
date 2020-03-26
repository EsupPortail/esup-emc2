<?php

namespace UnicaenUtilisateur\Form\Role;

trait RoleFormAwareTrait {

    /** @var RoleForm $roleForm */
    private $roleForm;

    /**
     * @return RoleForm
     */
    public function getRoleForm()
    {
        return $this->roleForm;
    }

    /**
     * @param RoleForm $roleForm
     * @return RoleForm
     */
    public function setRoleForm($roleForm)
    {
        $this->roleForm = $roleForm;
        return $this->roleForm;
    }
}