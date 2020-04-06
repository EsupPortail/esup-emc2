<?php

namespace UnicaenUtilisateur\View\Helper;

/**
 * Aide de vue permettant d'afficher le profil de l'utilisateur connecté.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier@unicaen.fr>
 */
class UserProfile extends UserAbstract
{
    /**
     * @var bool
     */
    protected $userProfileSelectable = false;

    /**
     * Point d'entrée.
     *
     * @param bool $userProfileSelectable Spécifie s'il faut afficher les profils
     *                                    de l'utilisateur sous forme d'une liste déroulante ou de boutons radios,
     *                                    permettant ainsi à l'utilisateur de changer de profil courant.
     * @return self
     */
    public function __invoke($userProfileSelectable = false)
    {
        $this->userProfileSelectable = $userProfileSelectable;

        return $this;
    }

    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function render()
    {
        $title   = _("Profil utilisateur");
        $unknown = _("Inconnu");
        $none    = _("Aucun");

        if ($this->getTranslator()) {
            $title   = $this->getTranslator()->translate($title, $this->getTranslatorTextDomain());
            $unknown = $this->getTranslator()->translate($unknown, $this->getTranslatorTextDomain());
            $none    = $this->getTranslator()->translate($none, $this->getTranslatorTextDomain());
        }

        $roles = $this->getIdentityRolesAsOptions();

        if (!$roles) {
            $roles[] = $none;
        }

        $html = "<strong>$title :</strong>" . PHP_EOL;

        if ($this->userProfileSelectable) {
            $html .= $this->view->userProfileSelect(false);
        } else {
            $html .= $this->view->htmlList($roles);
        }

        return $html;
    }

    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Retourne les rôles de l'utilisateur courant.
     *
     * @return array
     */
    protected function getIdentityRolesAsOptions()
    {
        return $this->getUserContext()->getIdentityRolesToString();
    }

    /**
     * Spécifie s'il faut afficher les profils
     * de l'utilisateur sous forme d'une liste déroulante ou de boutons radios, permettant
     * ainsi à l'utilisateur de changer de profil courant.
     *
     * @param bool $userProfileSelectable
     * @return UserProfile
     */
    public function setUserProfileSelectable($userProfileSelectable = true)
    {
        $this->userProfileSelectable = $userProfileSelectable;

        return $this;
    }
}