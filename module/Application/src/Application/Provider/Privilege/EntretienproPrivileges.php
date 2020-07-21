<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class EntretienproPrivileges extends Privileges
{
    const ENTRETIENPRO_INDEX        = 'entretienpro-entretienpro_index';
    const ENTRETIENPRO_AFFICHER     = 'entretienpro-entretienpro_afficher';
    const ENTRETIENPRO_AJOUTER      = 'entretienpro-entretienpro_ajouter';
    const ENTRETIENPRO_MODIFIER     = 'entretienpro-entretienpro_modifier';
    const ENTRETIENPRO_HISTORISER   = 'entretienpro-entretienpro_historiser';
    const ENTRETIENPRO_DETRUIRE     = 'entretienpro-entretienpro_detruire';

    const ENTRETIENPRO_VALIDER_AGENT = 'entretienpro-entretienpro_valider_agent';
    const ENTRETIENPRO_VALIDER_RESPONSABLE = 'entretienpro-entretienpro_valider_responsable';
    const ENTRETIENPRO_VALIDER_DRH = 'entretienpro-entretienpro_valider_drh';

    const ENTRETIENPRO_OBSERVATION_AJOUTER = 'entretienpro-entretienpro_observation_ajouter';
    const ENTRETIENPRO_OBSERVATION_MODIFIER = 'entretienpro-entretienpro_observation_modifier';
    const ENTRETIENPRO_OBSERVATION_HISTORISER = 'entretienpro-entretienpro_observation_historiser';
    const ENTRETIENPRO_OBSERVATION_DETRUIRE = 'entretienpro-entretienpro_observation_detruire';
}