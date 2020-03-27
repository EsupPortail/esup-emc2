<?php

namespace Indicateur\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class IndicateurPrivileges extends Privileges
{
    const AFFICHER      = 'indicateur-afficher-indicateur';
    const EDITER        = 'indicateur-editer-indicateur';
    const DETRUIRE      = 'indicateur-detruire-indicateur';
}