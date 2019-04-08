<?php

namespace Autoform\Provider\Privilege;

class ValidationPrivileges extends \UnicaenAuth\Provider\Privilege\Privileges
{
    const AFFICHER    = 'autoform-afficher-validation';
    const CREER       = 'autoform-creer-validation';
    const MODIFIER    = 'autoform-modifier-validation';
    const HISTORISER  = 'autoform-historiser-validation';
    const DETRUIRE    = 'autoform-detruire-validation';
}