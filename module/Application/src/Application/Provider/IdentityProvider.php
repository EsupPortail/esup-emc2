<?php

namespace Application\Provider;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;

use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Provider\Identity\AbstractIdentityProvider;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IdentityProvider extends AbstractIdentityProvider
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $code
     * @return User[]|null
     */
    public function computeUsersAutomatiques(string $code) : ?array
    {
        switch ($code) {
            case Agent::ROLE_AGENT :
                $user = $this->getAgentService()->getUsersInAgent();
                return $user;
            case Agent::ROLE_SUPERIEURE :
                $user = $this->getAgentSuperieurService()->getUsersInSuperieurs();
                return $user;
            case Agent::ROLE_AUTORITE :
                $user = $this->getAgentAutoriteService()->getUsersInAutorites();
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
            $roleAgent = $this->getRoleService()->findByRoleId(Agent::ROLE_AGENT);
            $roles[] = $roleAgent;

            $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent);
            if (!empty($superieurs)) {
                $roleSuperieur = $this->getRoleService()->findByRoleId(Agent::ROLE_SUPERIEURE);
                $roles[] = $roleSuperieur;
            }

            $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent);
            if (!empty($autorites)) {
                $roleAutorite = $this->getRoleService()->findByRoleId(Agent::ROLE_AUTORITE);
                $roles[] = $roleAutorite;
            }
        }
        return $roles;
    }

}