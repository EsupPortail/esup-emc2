<?php

namespace Autoform\Provider\Privilege;

class ChampPrivileges extends \UnicaenAuth\Provider\Privilege\Privileges
{
    const AFFICHER    = 'autoform-afficher-champ';
    const CREER       = 'autoform-creer-champ';
    const MODIFIER    = 'autoform-modifier-champ';
    const HISTORISER  = 'autoform-historiser-champ';
    const DETRUIRE    = 'autoform-detruire-champ';
}