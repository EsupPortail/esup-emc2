<?php

namespace UnicaenUtilisateur\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class UtilisateurPrivileges extends Privileges
{
    const UTILISATEUR_AFFICHER  = 'utilisateur-utilisateur_afficher';
    const UTILISATEUR_AJOUTER   = 'utilisateur-utilisateur_ajouter';
    const UTILISATEUR_MODIFIERROLE = 'utilisateur-utilisateur_modifierrole';
    const UTILISATEUR_CHANGERSTATUS = 'utilisateur-utilisateur_changerstatus';
}
