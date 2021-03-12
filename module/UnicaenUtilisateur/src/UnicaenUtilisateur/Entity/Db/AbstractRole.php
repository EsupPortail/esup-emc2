<?php

namespace UnicaenUtilisateur\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractRole implements RoleInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $roleId;

    /**
     * @var boolean
     */
    protected $isDefault = false;

    /**
     * @var Role
     */
    protected $parent;

    /**
     * @var string
     */
    protected $ldapFilter;

    /**
     * @var ArrayCollection
     */
    protected $users;

    /**
     * AbstractRole constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Get the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id.
     *
     * @param int $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Get the role id.
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set the role id.
     *
     * @param string $roleId
     *
     * @return self
     */
    public function setRoleId($roleId)
    {
        $this->roleId = (string)$roleId;

        return $this;
    }

    /**
     * Is this role the default one ?
     *
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set this role as the default one.
     *
     * @param boolean $isDefault
     *
     * @return self
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = (boolean)$isDefault;

        return $this;
    }

    /**
     * Get the parent role
     *
     * @return Role
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the parent role.
     *
     * @param RoleInterface $parent
     *
     * @return self
     */
    public function setParent(RoleInterface $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return string
     */
    public function getLdapFilter()
    {
        return $this->ldapFilter;
    }

    /**
     * @param string $ldapFilter
     *
     * @return Role
     */
    public function setLdapFilter($ldapFilter)
    {
        $this->ldapFilter = $ldapFilter;

        return $this;
    }

    /**
     * Get users.
     *
     * @return array
     */
    public function getUsers()
    {
        return $this->users->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addUser(UserInterface $user)
    {
        $this->users[] = $user;
    }

    /**
     * @param UserInterface $user
     */
    public function removeUser(UserInterface $user)
    {
        $this->users->removeElement($user);
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRoleId();
    }
}