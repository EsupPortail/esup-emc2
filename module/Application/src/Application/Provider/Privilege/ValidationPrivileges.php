<?php

namespace Application\Provider\Privilege;

class ValidationPrivileges extends \UnicaenAuth\Provider\Privilege\Privileges
{
    const AFFICHER         = 'validation-afficher-validation-main';
    const MODIFIER_DEMANDE = 'validation-modifier-demande';
    const DETRUIRE_DEMANDE = 'validation-detruire-demande';
    const CREER_DEMANDE    = 'validation-creer-demande';
}
