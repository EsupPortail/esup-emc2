<?php

namespace Carriere\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class CorpsPrivileges extends Privileges
{
    const CORPS_INDEX    = 'corps-corps_index';
    const CORPS_AFFICHER = 'corps-corps_afficher';
    const CORPS_MODIFIER = 'corps-corps_modifier';
    const CORPS_LISTER_AGENTS = 'corps-corps_lister_agents';
}