<?php

namespace Carriere\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class CorrespondancePrivileges extends Privileges
{
    const CORRESPONDANCE_INDEX    = 'correspondance-correspondance_index';
    const CORRESPONDANCE_AFFICHER = 'correspondance-correspondance_afficher';
    const CORRESPONDANCE_AJOUTER = 'correspondance-correspondance_ajouter';
    const CORRESPONDANCE_MODIFIER = 'correspondance-correspondance_modifier';
    const CORRESPONDANCE_SUPPRIMER = 'correspondance-correspondance_supprimer';
    const CORRESPONDANCE_LISTER_AGENTS = 'correspondance-correspondance_lister_agents';
}