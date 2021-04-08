<?php

namespace UnicaenUtilisateur\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenPrivilege\Entity\Db\Privilege;

class Role extends AbstractRole
{
    /** @var string */
    private $libelle;
    /** @var Collection (Privilege)*/
    private $privilege;

    public function __construct()
    {
        parent::__construct();
        $this->privilege = new ArrayCollection();
    }

    /**
     * @param UserInterface $user
     * @return Collection
     */
    public function addUser(UserInterface $user)
    {
        $this->users[] = $user;
        return $this->users;
    }

    /**
     * @param UserInterface $user
     * @return Collection
     */
    public function removeUser(UserInterface $user)
    {
        $this->users->removeElement($user);
        return $this->users;
    }

    /**
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param Privilege $privilege
     * @return Role
     */
    public function addPrivilege(Privilege $privilege)
    {
        $this->privilege[] = $privilege;
        return $this;
    }

    /**
     * @param Privilege $privilege
     */
    public function removePrivilege(Privilege $privilege)
    {
        $this->privilege->removeElement($privilege);
    }

    /**
     * @return Collection
     */
    public function getPrivileges()
    {
        return $this->privilege;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return Role
     */
    public function setLibelle(?string $libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }
}

