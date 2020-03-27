<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ValidationPrivileges extends Privileges
{
    const AFFICHER         = 'validation-afficher-validation-main';
    const MODIFIER         = 'validation-modifier-validation-main';
    const MODIFIER_DEMANDE = 'validation-modifier-demande';
    const DETRUIRE_DEMANDE = 'validation-detruire-demande';
    const CREER_DEMANDE    = 'validation-creer-demande';

}
