<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class CampagnePrivileges extends Privileges
{
    const CAMPAGNE_AJOUTER = 'campagne-campagne_ajouter';
    const CAMPAGNE_HISTORISER = 'campagne-campagne_historiser';
    const CAMPAGNE_AFFICHER = 'campagne-campagne_afficher';
    const CAMPAGNE_MODIFIER = 'campagne-campagne_modifier';
    const CAMPAGNE_DETRUIRE = 'campagne-campagne_detruire';
}