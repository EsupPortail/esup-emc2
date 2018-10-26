<?php

namespace Application\Entity\Db;
use UnicaenAuth\Entity\Db\AbstractRole;
use UnicaenAuth\Entity\Db\UserInterface;

/**
 * Role
 */
class Role extends AbstractRole
{
    const ADMIN_TECH = 'Administrateur technique';
    const ADMIN_FONC = 'Administrateur fonctionnel';
    const UTILISATEUR = 'Utilisateur';

    /** @var \Doctrine\Common\Collections\Collection */
    private $privilege;

    public function __construct()
    {
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
}

