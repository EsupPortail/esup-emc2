<?php

namespace Autoform\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ValidationPrivileges extends Privileges
{
    const AFFICHER    = 'autoform-afficher-validation';
    const CREER       = 'autoform-creer-validation';
    const MODIFIER    = 'autoform-modifier-validation';
    const HISTORISER  = 'autoform-historiser-validation';
    const DETRUIRE    = 'autoform-detruire-validation';
}