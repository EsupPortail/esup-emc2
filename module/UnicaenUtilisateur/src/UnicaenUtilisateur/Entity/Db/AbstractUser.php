<?php

namespace UnicaenUtilisateur\Entity\Db;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuResultatInterface;

abstract class AbstractUser implements UserInterface, ProviderInterface, RechercheIndividuResultatInterface
{
    const PASSWORD_LDAP = 'ldap';
    const PASSWORD_SHIB = 'shib';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var int
     */
    protected $state;

    /**
     * @var string
     */
    protected $passwordResetToken;

    /**
     * @var ArrayCollection
     */
    protected $roles;

    /**
     * Initialies the roles variable.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     * @return self
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    /**
     * @param string $passwordResetToken
     * @return self
     */
    public function setPasswordResetToken($passwordResetToken = null)
    {
        $this->passwordResetToken = $passwordResetToken;

        return $this;
    }

    /**
     * Get role.
     *
     * @return Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add a role to the user.
     *
     * @param RoleInterface $role
     * @return self
     */
    public function addRole(RoleInterface $role)
    {
        $this->roles->add($role);

        return $this;
    }

    /**
     * Retourne true si cet utilisateur est local.
     *
     * Un utilisateur est local s'il ne résulte pas d'une authentification LDAP ou Shibboleth.
     * Son mot de passe est chiffré dans la table des utilisateurs.
     *
     * @return bool
     */
    public function isLocal()
    {
        return ! in_array($this->getPassword(), [
            AbstractUser::PASSWORD_LDAP,
            AbstractUser::PASSWORD_SHIB,
        ]);
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getDisplayName();
    }
}