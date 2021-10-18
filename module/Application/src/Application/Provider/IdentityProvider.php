<?php

namespace Application\Provider;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use BjyAuthorize\Provider\Identity\ProviderInterface;
use UnicaenAuthentification\Provider\Identity\ChainableProvider;
use UnicaenAuthentification\Provider\Identity\ChainEvent;

use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IdentityProvider implements ProviderInterface, ChainableProvider
{
    use AgentServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;

    private $roles;

    /**
     * @return string[]|RoleInterface[]
     */
    public function getIdentityRoles()
    {
        $this->roles = [];

        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        if ($agent !== null) {
            $roleAgent = $this->getRoleService()->getRoleByCode('Agent');
            $this->roles[] = $roleAgent;
        }

        $responsabilites = $this->getAgentService()->getResposabiliteStructure($agent);
        if ($responsabilites !== null AND $responsabilites !== []) {
            $roleResponsable = $this->getRoleService()->getRoleByCode('Responsable de structure');
            $this->roles[] = $roleResponsable;
        }

        return $this->roles;
    }

    /**
     * @param ChainEvent $event
     */
    public function injectIdentityRoles(ChainEvent $event) {
        $event->addRoles($this->getIdentityRoles());
    }
}