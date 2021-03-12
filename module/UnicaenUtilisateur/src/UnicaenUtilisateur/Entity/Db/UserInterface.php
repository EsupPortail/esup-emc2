<?php

namespace UnicaenUtilisateur\Entity\Db;

use Doctrine\Common\Collections\Collection;

interface UserInterface extends \ZfcUser\Entity\UserInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int|null $id
     * @return self
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $username
     * @return self
     */
    public function setUsername($username);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return self
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getDisplayName();

    /**
     * @param string $displayName
     * @return self
     */
    public function setDisplayName($displayName);

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword();

    /**
     * @param string $password
     * @return self
     */
    public function setPassword($password);

    /**
     * @return int
     */
    public function getState();

    /**
     * @param int $state
     * @return self
     */
    public function setState($state);

    /**
     * @return Collection
     */
    public function getRoles();

    /**
     * @param RoleInterface $role
     * @return self
     */
    public function addRole(RoleInterface $role);

    /**
     * @return string
     */
    public function __toString();
}