<?php

namespace EntretienProfessionnel\Provider;

use Application\Service\Agent\AgentServiceAwareTrait;
use BjyAuthorize\Provider\Identity\ProviderInterface;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnelConstant;
use EntretienProfessionnel\Service\Delegue\DelegueServiceAwareTrait;
use UnicaenAuthentification\Provider\Identity\ChainableProvider;
use UnicaenAuthentification\Provider\Identity\ChainEvent;

use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IdentityProvider implements ProviderInterface, ChainableProvider
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
            case EntretienProfessionnelConstant::ROLE_DELEGUE :
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
                $roleDelegue = $this->getRoleService()->findByRoleId(EntretienProfessionnelConstant::ROLE_DELEGUE);
                $roles[] = $roleDelegue;
            }
        }

        return $roles;
    }

    /**
     * @return string[]|RoleInterface[]
     */
    public function getIdentityRoles()
    {
        return $this->computeRolesAutomatiques();
    }

    /**
     * @param ChainEvent $e
     */
    public function injectIdentityRoles(ChainEvent $e) {
        $e->addRoles($this->getIdentityRoles());
    }
}