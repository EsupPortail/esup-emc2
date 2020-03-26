<?php

namespace UnicaenUtilisateur\Entity\Db;

use UnicaenPrivilege\Entity\Db\Privilege;

class Role extends AbstractRole
{
    /** @var string */
    private $libelle;
    /** @var \Doctrine\Common\Collections\Collection (Privilege)*/
    private $privilege;

    public function __construct()
    {
        parent::__construct();
        $this->privilege = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param UserInterface $user
     * @return \Doctrine\Common\Collections\Collection
     */
    public function addUser(UserInterface $user)
    {
        $this->users[] = $user;
        return $this->users;
    }

    /**
     * @param UserInterface $user
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeUser(UserInterface $user)
    {
        $this->users->removeElement($user);
        return $this->users;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
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
     * @return \Doctrine\Common\Collections\Collection
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
     * @param string $libelle
     * @return Role
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }
}

