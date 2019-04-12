<?php

namespace Utilisateur\Entity\Db;

//use UnicaenApp\Entity\UserInterface;
use UnicaenAuth\Entity\Db\AbstractUser;

class User extends AbstractUser //implements UserInterface
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

