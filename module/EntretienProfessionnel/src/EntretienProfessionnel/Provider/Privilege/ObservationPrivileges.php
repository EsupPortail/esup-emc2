<?php

namespace EntretienProfessionnel\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ObservationPrivileges extends Privileges
{
    const OBSERVATION_AFFICHER = 'observation-observation_afficher';
    const OBSERVATION_AJOUTER = 'observation-observation_ajouter';
    const OBSERVATION_MODIFIER = 'observation-observation_modifier';
    const OBSERVATION_HISTORISER = 'observation-observation_historiser';
    const OBSERVATION_SUPPRIMER = 'observation-observation_supprimer';
}
