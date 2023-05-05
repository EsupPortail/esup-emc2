<?php

namespace Structure\Provider;

use Application\Service\Agent\AgentServiceAwareTrait;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenAuthentification\Provider\Identity\ChainEvent;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Provider\Identity\AbstractIdentityProvider;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IdentityProvider extends AbstractIdentityProvider
{
    use AgentServiceAwareTrait;
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $code
     * @return User[]|null
     */
    public function computeUsersAutomatiques(string $code) : ?array
    {
        switch ($code) {
            case RoleProvider::RESPONSABLE :
                $user = $this->getStructureService()->getUsersInResponsables();
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
            $responsabilites = $this->getAgentService()->getResposabiliteStructure($agent);
            if ($responsabilites !== null and $responsabilites !== []) {
                $roleResponsable = $this->getRoleService()->findByRoleId(RoleProvider::RESPONSABLE);
                $roles[] = $roleResponsable;
            }
        }
        return $roles;
    }

}