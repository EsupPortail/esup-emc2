<?php
/**
 * Created by PhpStorm.
 * User: metivier
 * Date: 14/12/18
 * Time: 12:06
 */

namespace Application\Provider\Privilege;


use UnicaenPrivilege\Provider\Privilege\Privileges;

class AgentPrivileges extends Privileges
{
    const AGENT_INDEX             = 'agent-agent_index';
    const AGENT_AFFICHER          = 'agent-agent_afficher';
    const AGENT_AJOUTER           = 'agent-agent_ajouter';
    const AGENT_EDITER            = 'agent-agent_editer';
    const AGENT_EFFACER           = 'agent-agent_effacer';

    const AGENT_ENTRETIENPRO_VOIR           = 'agent-agent_entretienpro_voir';
    const AGENT_ENTRETIENPRO_AJOUTER        = 'agent-agent_entretienpro_ajouter';
    const AGENT_ENTRETIENPRO_MODIFIER       = 'agent-agent_entretienpro_modifier';
    const AGENT_ENTRETIENPRO_HISTORISER     = 'agent-agent_entretienpro_historiser';
    const AGENT_ENTRETIENPRO_DETRUIRE       = 'agent-agent_entretienpro_detruire';

    const AGENT_FICHIER_AFFICHER = 'agent-agent_fichier_afficher';
    const AGENT_FICHIER_AJOUTER = 'agent-agent_fichier_ajouter';
    const AGENT_FICHIER_MODIFIER = 'agent-agent_fichier_modifier';
    const AGENT_FICHIER_HISTORISER = 'agent-agent_fichier_historiser';
    const AGENT_FICHIER_DETRUIRE = 'agent-agent_fichier_detruire';
}