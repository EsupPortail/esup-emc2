<?php

namespace Utilisateur\Entity\Db;

use Application\Entity\Db\Structure;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\UserInterface;
use UnicaenAuth\Entity\Db\AbstractUser;

class User extends AbstractUser implements UserInterface
{

    /** @var ArrayCollection (Structure) */
    private $structures;

    public function __construct()
    {
        $this->structures = new ArrayCollection();
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

    /** @return Structure[] */
    public function getStructures()
    {
        return $this->structures->toArray();
    }
}

