<?php

namespace UnicaenUtilisateur\Entity\Db;

use BjyAuthorize\Acl\HierarchicalRoleInterface;

interface RoleInterface extends HierarchicalRoleInterface
{
    /**
     * @param int $id
     * @return self
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getRoleId();

    /**
     * @param string $roleId
     * @return self
     */
    public function setRoleId($roleId);

    /**
     * @return boolean
     */
    public function getIsDefault();

    /**
     * @param boolean $isDefault
     * @return self
     */
    public function setIsDefault($isDefault);

    /**
     * @return RoleInterface|null
     */
    public function getParent();

    /**
     * @param RoleInterface|null $parent
     * @return self
     */
    public function setParent(RoleInterface $parent = null);

    /**
     * @return string
     */
    public function getLdapFilter();

    /**
     * @param string $ldapFilter
     * @return self
     */
    public function setLdapFilter($ldapFilter);

    /**
     * @return UserInterface[]
     */
    public function getUsers();

    /**
     * @param UserInterface $user
     * @return self
     */
    public function addUser(UserInterface $user);

    /**
     * @return string
     */
    public function __toString();
}