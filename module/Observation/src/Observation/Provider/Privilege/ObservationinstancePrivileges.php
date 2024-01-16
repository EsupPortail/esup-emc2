<?php

namespace Observation\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ObservationinstancePrivileges extends Privileges
{
    const OBSERVATIONINSTANCE_INDEX = 'observationinstance-observationinstance_index';
    const OBSERVATIONINSTANCE_AFFICHER = 'observationinstance-observationinstance_afficher';
    const OBSERVATIONINSTANCE_AJOUTER = 'observationinstance-observationinstance_ajouter';
    const OBSERVATIONINSTANCE_MODIFIER = 'observationinstance-observationinstance_modifier';
    const OBSERVATIONINSTANCE_HISTORISER = 'observationinstance-observationinstance_historiser';
    const OBSERVATIONINSTANCE_SUPPRIMER = 'observationinstance-observationinstance_supprimer';
}