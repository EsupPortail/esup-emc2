<?php

namespace UnicaenUtilisateur\Form\Role;

trait RoleFormAwareTrait {

    /** @var RoleForm $roleForm */
    private $roleForm;

    /**
     * @return RoleForm
     */
    public function getRoleForm() : RoleForm
    {
        return $this->roleForm;
    }

    /**
     * @param RoleForm $roleForm
     * @return RoleForm
     */
    public function setRoleForm(RoleForm $roleForm) : RoleForm
    {
        $this->roleForm = $roleForm;
        return $this->roleForm;
    }
}