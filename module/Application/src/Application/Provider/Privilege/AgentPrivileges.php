<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class AgentPrivileges extends Privileges
{
    const AGENT_INDEX             = 'agent-agent_index';
    const AGENT_AFFICHER          = 'agent-agent_afficher';
    const AGENT_AJOUTER           = 'agent-agent_ajouter';
    const AGENT_EDITER            = 'agent-agent_editer';
    const AGENT_EFFACER           = 'agent-agent_effacer';

    const AGENT_ELEMENT_VOIR                = 'agent-agent_element_voir';
    const AGENT_ELEMENT_AJOUTER             = 'agent-agent_element_ajouter';
    const AGENT_ELEMENT_MODIFIER            = 'agent-agent_element_modifier';
    const AGENT_ELEMENT_HISTORISER          = 'agent-agent_element_historiser';
    const AGENT_ELEMENT_DETRUIRE            = 'agent-agent_element_detruire';
    const AGENT_ELEMENT_VALIDER             = 'agent-agent_element_valider';
    const AGENT_ELEMENT_AJOUTER_EPRO        = 'agent-agent_element_ajouter_epro';
}