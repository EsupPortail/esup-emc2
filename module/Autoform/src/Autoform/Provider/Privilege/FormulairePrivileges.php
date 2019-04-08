<?php

namespace Autoform\Provider\Privilege;

class FormulairePrivileges extends \UnicaenAuth\Provider\Privilege\Privileges
{
    const AFFICHER    = 'autoform-afficher-formulaire';
    const CREER       = 'autoform-creer-formulaire';
    const MODIFIER    = 'autoform-modifier-formulaire';
    const HISTORISER  = 'autoform-historiser-formulaire';
    const DETRUIRE    = 'autoform-detruire-formulaire';
}