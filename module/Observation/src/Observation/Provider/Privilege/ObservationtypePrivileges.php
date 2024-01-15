<?php

namespace Observation\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ObservationtypePrivileges extends Privileges
{
    const OBSERVATIONTYPE_INDEX = 'observationtype-observationtype_index';
    const OBSERVATIONTYPE_AFFICHER = 'observationtype-observationtype_afficher';
    const OBSERVATIONTYPE_AJOUTER = 'observationtype-observationtype_ajouter';
    const OBSERVATIONTYPE_MODIFIER = 'observationtype-observationtype_modifier';
    const OBSERVATIONTYPE_HISTORISER = 'observationtype-observationtype_historiser';
    const OBSERVATIONTYPE_SUPPRIMER = 'observationtype-observationtype_supprimer';
}