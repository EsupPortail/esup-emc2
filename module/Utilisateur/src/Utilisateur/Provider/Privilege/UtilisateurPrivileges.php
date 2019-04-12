<?php

namespace Utilisateur\Provider\Privilege;

class UtilisateurPrivileges extends \UnicaenAuth\Provider\Privilege\Privileges
{
    const AFFICHER          = 'utilisateur-afficher-utilisateur';
    const AJOUTER           = 'utilisateur-ajouter-utilisateur';
    const CHANGER_STATUS    = 'utilisateur-changer-status';
    const MODIFIER_ROLE     = 'utilisateur-modifier-role';
}
