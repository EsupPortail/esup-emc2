<?php

namespace UnicaenUtilisateur\Formatter;

use Zend\Permissions\Acl\Role\RoleInterface;

class RoleFormatter
{
    /**
     * Retourne le rôle spécifié au format littéral.
     *
     * @param object|RoleInterface|string $role
     * @return string
     *
     * @see formatOne()
     */
    public function format($role)
    {
        return $this->formatOne($role);
    }

    /**
     * Retourne le rôle spécifié au format littéral.
     *
     * @param object|RoleInterface|string $role
     * @return string
     */
    public function formatOne($role)
    {
        $formattedRole = '?';

        if ($role instanceof RoleInterface) {
            $formattedRole = $role->getRoleId();
        }
        elseif (is_string($role)) {
            $formattedRole = (string) $role;
        }
        elseif (is_object($role) && method_exists($role, '__toString')) {
            $formattedRole = $role;
        }

        return $formattedRole;
    }

    /**
     * Retourne les rôles spécifiés au format littéral.
     *
     * @param object[]|RoleInterface[]|string[] $roles
     * @return string[]
     */
    public function formatMany(array $roles)
    {
        $formattedRoles = [];

        foreach ($roles as $key => $role) {
            if ($role instanceof RoleInterface) {
                $key = $role->getRoleId();
            }
            elseif (is_string($role)) {
                $key = $role;
            }
            $formattedRoles[$key] = $this->formatOne($role);
        }

        return $formattedRoles;
    }
}