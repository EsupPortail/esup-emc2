<?php

namespace Formation\Provider;

use Formation\Provider\Role\FormationRoles;
use Formation\Service\Formateur\FormateurServiceAwareTrait;
use Formation\Service\StagiaireExterne\StagiaireExterneServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Provider\Identity\AbstractIdentityProvider;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IdentityProvider extends AbstractIdentityProvider
{
    use FormateurServiceAwareTrait;
    use StagiaireExterneServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $code
     * @return User[]|null
     */
    public function computeUsersAutomatiques(string $code): ?array
    {
        switch ($code)  {
            case FormationRoles::STAGIAIRE_EXTERNE :
                $user = $this->getStagiaireExterneService()->getUsersInStagiaireExterne();
                return $user;
            case FormationRoles::FORMATEUR :
                $user = $this->getFormateurService()->getUsersInFormateur();
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

        $stagiaire = $this->getStagiaireExterneService()->getStagiaireExterneByUser($user);
        if ($stagiaire !== null) {
            $roleStagiaire = $this->getRoleService()->findByRoleId(FormationRoles::STAGIAIRE_EXTERNE);
            $roles[] = $roleStagiaire;
        }
        $formateur = $this->getFormateurService()->getFormateursByUser($user);
        if (!empty($formateur)) {
            $roleFormateur = $this->getRoleService()->findByRoleId(FormationRoles::FORMATEUR);
            $roles[] = $roleFormateur;
        }
        return $roles;
    }
}