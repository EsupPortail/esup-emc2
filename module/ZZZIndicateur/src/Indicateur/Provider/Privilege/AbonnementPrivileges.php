<?php

namespace Indicateur\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class AbonnementPrivileges extends Privileges
{
    const AFFICHER      = 'indicateur-afficher-abonnement';
    const EDITER        = 'indicateur-editer-abonnement';
    const DETRUIRE      = 'indicateur-detruire-abonnement';
}