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
    const AFFICHER          = 'agent-afficher-agent';
    const AJOUTER           = 'agent-ajouter-agent';
    const EDITER            = 'agent-editer-agent';
    const EFFACER           = 'agent-effacer-agent';

}