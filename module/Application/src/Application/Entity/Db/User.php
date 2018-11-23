<?php

namespace Application\Entity\Db;

use UnicaenAuth\Entity\Db\AbstractUser;

class User extends AbstractUser
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->displayName;
    }

    function hasRole($roleId) {
        /** @var Role $role */
        foreach ($this->getRoles() as $role) {
            if ($role->getRoleId() === $roleId) return true;
        }
        return false;
    }

    public function removeRole($role)
    {
        $this->roles->removeElement($role);
    }
}

