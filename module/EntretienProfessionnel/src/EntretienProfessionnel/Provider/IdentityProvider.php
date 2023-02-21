<?php

namespace EntretienProfessionnel\Provider;

use Application\Service\Agent\AgentServiceAwareTrait;
use EntretienProfessionnel\Provider\Role\EntretienProfessionnelRoles;
use EntretienProfessionnel\Service\Delegue\DelegueServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Provider\Identity\AbstractIdentityProvider;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IdentityProvider extends AbstractIdentityProvider
{
    use AgentServiceAwareTrait;
    use DelegueServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $code
     * @return User[]|null
     */
    public function computeUsersAutomatiques(string $code) : ?array
    {
        switch ($code) {
            case EntretienProfessionnelRoles::ROLE_DELEGUE :
                $user = $this->getDelegueService()->getUsersInDelegue();
                return $user;
        }
        return null;
    }

    /**
     * @param User|null $user
     * @return string[]|RoleInterface[]
     */
    public function computeRolesAutomatiques(?User $user = null) : array
    {
        $roles = [];

        if ($user === null) {
            $user = $this->getUserService()->getConnectedUser();
        }

        $agent = $this->getAgentService()->getAgentByUser($user);
        if ($agent !== null) {
            $deleguations = $this->getDelegueService()->getDeleguesByAgent($agent);
            if ($deleguations !== null and $deleguations !== []) {
                $roleDelegue = $this->getRoleService()->findByRoleId(EntretienProfessionnelRoles::ROLE_DELEGUE);
                $roles[] = $roleDelegue;
            }
        }

        return $roles;
    }
}