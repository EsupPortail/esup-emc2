<?php

namespace UnicaenUtilisateur\Event;

use UnicaenPrivilege\Service\UserContext;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Event\Listener\UserRoleSelectedEventAbstractListener;
use Zend\Permissions\Acl\Role\RoleInterface;

class UserRoleSelectedEventListener extends UserRoleSelectedEventAbstractListener
{
    /**
     * @param UserRoleSelectedEvent $e
     */
    public function postSelection(UserRoleSelectedEvent $e)
    {
        $role = $e->getRole();

        if (! $role) {
            return;
        }

        if (! $role instanceof RoleInterface) {
            $role = $this->getEntityManager()->getRepository(Role::class)->findOneBy(['roleId' => $role]);
        }

        /** @var UserContext $userContext */
        $userContext = $e->getTarget();

        /** @var User $utilisateur */
        $utilisateur = $userContext->getDbUser();
        if (! $utilisateur) {
            return;
        }

        $this->saveUserLastRole($utilisateur, $role);
    }

    private function saveUserLastRole(User $dbUser, $role)
    {
        $dbUser->setLastRole($role);
        $this->getEntityManager()->flush($dbUser);
    }
}