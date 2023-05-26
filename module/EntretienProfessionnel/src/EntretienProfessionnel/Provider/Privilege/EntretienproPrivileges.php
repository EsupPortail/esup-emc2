<?php

namespace EntretienProfessionnel\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class EntretienproPrivileges extends Privileges
{
    const ENTRETIENPRO_INDEX        = 'entretienpro-entretienpro_index';
    const ENTRETIENPRO_MESENTRETIENS = 'entretienpro-entretienpro_mesentretiens';
    const ENTRETIENPRO_AFFICHER     = 'entretienpro-entretienpro_afficher';
    const ENTRETIENPRO_EXPORTER     = 'entretienpro-entretienpro_exporter';

    const ENTRETIENPRO_CONVOQUER    = 'entretienpro-entretienpro_convoquer';
    const ENTRETIENPRO_MODIFIER     = 'entretienpro-entretienpro_modifier';
    const ENTRETIENPRO_HISTORISER   = 'entretienpro-entretienpro_historiser';
    const ENTRETIENPRO_DETRUIRE     = 'entretienpro-entretienpro_detruire';

    const ENTRETIENPRO_VALIDER_AGENT = 'entretienpro-entretienpro_valider_agent';
    const ENTRETIENPRO_VALIDER_RESPONSABLE = 'entretienpro-entretienpro_valider_responsable';
    const ENTRETIENPRO_VALIDER_DRH = 'entretienpro-entretienpro_valider_drh';
    const ENTRETIENPRO_VALIDER_OBSERVATION = 'entretienpro-entretienpro_valider_observation';
}