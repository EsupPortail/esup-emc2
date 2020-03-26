<?php

namespace UnicaenUtilisateur\Event;

use Zend\EventManager\Event;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Classe des événements déclenchés lors l'utilisateur a sélectionné rôle.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserRoleSelectedEvent extends Event
{
    const POST_SELECTION = 'postSelection';

    private $role;

    /**
     * @return RoleInterface|string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param RoleInterface|string $role
     * @return self
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return static
     */
    static public function postSelection()
    {
        return new static(static::POST_SELECTION);
    }
}