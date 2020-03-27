<?php

namespace Autoform\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ChampPrivileges extends Privileges
{
    const AFFICHER    = 'autoform-afficher-champ';
    const CREER       = 'autoform-creer-champ';
    const MODIFIER    = 'autoform-modifier-champ';
    const HISTORISER  = 'autoform-historiser-champ';
    const DETRUIRE    = 'autoform-detruire-champ';
}