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
    const AGENT_AFFICHER          = 'agent-agent_afficher';
    const AGENT_AJOUTER           = 'agent-agent_ajouter';
    const AGENT_EDITER            = 'agent-agent_editer';
    const AGENT_EFFACER           = 'agent-agent_effacer';

}