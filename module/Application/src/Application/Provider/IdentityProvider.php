<?php

namespace Application\Provider;

use Application\Entity\Db\Structure;
use Application\Service\Agent\AgentServiceAwareTrait;
use BjyAuthorize\Provider\Identity\ProviderInterface;
use EntretienProfessionnel\Entity\Db\Delegue;
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
    use RoleServiceAwareTrait;
    use DelegueServiceAwareTrait;
    use UserServiceAwareTrait;

    private $roles;

    /**
     * @param User|null $user
     * @return string[]|RoleInterface[]
     */
    public function computeRolesAutomatiques(?User $user = null)
    {
        $roles = [];

        if ($user === null) {
            $user = $this->getUserService()->getConnectedUser();
        }

        $agent = $this->getAgentService()->getAgentByUser($user);
        if ($agent !== null) {
            $roleAgent = $this->getRoleService()->getRoleByCode('Agent');
            $roles[] = $roleAgent;

            $responsabilites = $this->getAgentService()->getResposabiliteStructure($agent);
            if ($responsabilites !== null and $responsabilites !== []) {
                $roleResponsable = $this->getRoleService()->getRoleByCode(Structure::ROLE_RESPONSABLE);
                $roles[] = $roleResponsable;
            }

            $gestions = $this->getAgentService()->getGestionnaireStructure($agent);
            if ($gestions !== null and $gestions !== []) {
                $roleGestionnaire = $this->getRoleService()->getRoleByCode(Structure::ROLE_GESTIONNAIRE);
                $roles[] = $roleGestionnaire;
            }

            $deleguations = $this->getDelegueService()->getDeleguesByAgent($agent);
            if ($deleguations !== null and $deleguations !== []) {
                $roleDelegue = $this->getRoleService()->getRoleByCode(Delegue::ROLE_DELEGUE);
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
        $this->roles = $this->roles = $this->computeRolesAutomatiques();
        return $this->roles;
    }

    /**
     * @param ChainEvent $event
     */
    public function injectIdentityRoles(ChainEvent $event) {
        $event->addRoles($this->getIdentityRoles());
    }
}