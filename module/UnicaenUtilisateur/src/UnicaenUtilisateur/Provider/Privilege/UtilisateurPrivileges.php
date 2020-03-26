<?php

namespace UnicaenUtilisateur\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class UtilisateurPrivileges extends Privileges
{
    const UTILISATEUR_AFFICHER     = 'utilisateur-utilisateur_afficher';
    const UTILISATEUR_AJOUTER   = 'utilisateur-utilisateur_ajouter';
    const STATUT_CHANGER        = 'utilisateur-statut_changer';
    const MODIFIER_ROLE         = 'utilisateur-modifier_role';
}
