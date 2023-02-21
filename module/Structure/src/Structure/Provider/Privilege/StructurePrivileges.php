<?php

namespace Structure\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class StructurePrivileges extends Privileges
{
    const STRUCTURE_INDEX               = 'structure-structure_index';
    const STRUCTURE_AFFICHER            = 'structure-structure_afficher';
    const STRUCTURE_DESCRIPTION         = 'structure-structure_description';
    const STRUCTURE_GESTIONNAIRE        = 'structure-structure_gestionnaire'; //sert pour les délégués
    const STRUCTURE_COMPLEMENT_AGENT    = 'structure-structure_complement_agent';
    const STRUCTURE_AGENT_FORCE         = 'structure-structure_agent_force';
}
