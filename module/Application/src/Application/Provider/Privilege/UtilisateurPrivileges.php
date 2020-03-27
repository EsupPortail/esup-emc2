<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class UtilisateurPrivileges extends Privileges
{
    const AFFICHER          = 'utilisateur-afficher-utilisateur';
    const AJOUTER           = 'utilisateur-ajouter-utilisateur';
    const CHANGER_STATUS    = 'utilisateur-changer-status';
    const MODIFIER_ROLE     = 'utilisateur-modifier-role';
}
