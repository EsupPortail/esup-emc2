<?php

namespace Structure\Provider;

use Agent\Service\Agent\AgentServiceAwareTrait;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
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
    use ObservateurServiceAwareTrait;
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
            case RoleProvider::GESTIONNAIRE :
                $user = $this->getStructureService()->getUsersInGestionnaires();
                return $user;
            case RoleProvider::OBSERVATEUR :
                $user = $this->getObservateurService()->getUsersInObservateurs();
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
        $roleResponsable = $this->getRoleService()->findByRoleId(RoleProvider::RESPONSABLE);
        $roleGestionnaire = $this->getRoleService()->findByRoleId(RoleProvider::GESTIONNAIRE);
        $roleObservateur = $this->getRoleService()->findByRoleId(RoleProvider::OBSERVATEUR);

        if ($user === null) {
            $user = $this->getUserService()->getConnectedUser();
        }

        $agent = $this->getAgentService()->getAgentByUser($user);
        if ($agent !== null) {
            $responsabilites = $this->getAgentService()->getResposabiliteStructure($agent);
            if ($responsabilites !== null and $responsabilites !== []) {
                $roles[] = $roleResponsable;
            }
            $gestions = $this->getAgentService()->getGestionnaireStructure($agent);
            if ($gestions !== null and $gestions !== []) {
                $roles[] = $roleGestionnaire;
            }
        }

        $observateurs = $this->getObservateurService()->getObservateursByUtilisateur($user);
        if (! empty($observateurs)) {
            $roles[] = $roleObservateur;
        }
        return $roles;
    }

}