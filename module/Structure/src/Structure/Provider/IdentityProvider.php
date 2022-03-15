<?php

namespace Structure\Provider;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use BjyAuthorize\Provider\Identity\ProviderInterface;
use UnicaenAuthentification\Provider\Identity\ChainableProvider;
use UnicaenAuthentification\Provider\Identity\ChainEvent;

use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IdentityProvider implements ProviderInterface, ChainableProvider
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
            case Structure::ROLE_RESPONSABLE :
                $user = $this->getStructureService()->getUsersInResponsables();
                return $user;
            case Structure::ROLE_GESTIONNAIRE :
                $user = $this->getStructureService()->getUsersInGestionnaires();
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
                $roleResponsable = $this->getRoleService()->findByRoleId(Structure::ROLE_RESPONSABLE);
                $roles[] = $roleResponsable;
            }

            $gestions = $this->getAgentService()->getGestionnaireStructure($agent);
            if ($gestions !== null and $gestions !== []) {
                $roleGestionnaire = $this->getRoleService()->findByRoleId(Structure::ROLE_GESTIONNAIRE);
                $roles[] = $roleGestionnaire;
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