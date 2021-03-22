<?php

namespace UnicaenUtilisateur\Entity\Db;

class User extends AbstractUser implements UserInterface
{
    private $lastRole;

    /**
     * @return mixed
     */
    public function getLastRole()
    {
        return $this->lastRole;
    }

    /**
     * @param mixed $lastRole
     * @return User
     */
    public function setLastRole($lastRole)
    {
        $this->lastRole = $lastRole;
        return $this;
    }


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

    function hasRole($roleSearch) {
        /** @var Role $role */
        foreach ($this->getRoles() as $role) {
            if ($role === $roleSearch) return true;
        }
        return false;
    }

    public function removeRole($role)
    {
        $this->roles->removeElement($role);
    }
}

