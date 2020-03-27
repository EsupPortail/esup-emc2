<?php

namespace Autoform\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FormulairePrivileges extends Privileges
{
    const AFFICHER    = 'autoform-afficher-formulaire';
    const CREER       = 'autoform-creer-formulaire';
    const MODIFIER    = 'autoform-modifier-formulaire';
    const HISTORISER  = 'autoform-historiser-formulaire';
    const DETRUIRE    = 'autoform-detruire-formulaire';
}