<?php

namespace EntretienProfessionnel\Provider\Identity;

use EntretienProfessionnel\Provider\Role\RoleProvider;
use EntretienProfessionnel\Service\Observateur\ObservateurServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Provider\Identity\AbstractIdentityProvider;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IdentityProvider extends AbstractIdentityProvider
{
    use ObservateurServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $code
     * @return User[]|null
     */
    public function computeUsersAutomatiques(string $code): ?array
    {
        switch ($code) {
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
    public function computeRolesAutomatiques(?User $user = null): array
    {
        $roles = [];

        if ($user === null) {
            $user = $this->getUserService()->getConnectedUser();
        }

        if ($user !== null) {
            $observateurs = $this->getObservateurService()->getObservateursByUser($user);
            if (!empty($observateurs)) {
                $roleObservateur = $this->getRoleService()->findByRoleId(RoleProvider::OBSERVATEUR);
                $roles[] = $roleObservateur;
            }
        }
        return $roles;
    }

}