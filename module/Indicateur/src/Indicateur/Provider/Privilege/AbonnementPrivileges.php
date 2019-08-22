<?php

namespace Indicateur\Provider\Privilege;

class AbonnementPrivileges extends \UnicaenAuth\Provider\Privilege\Privileges
{
    const AFFICHER      = 'indicateur-afficher-abonnement';
    const EDITER        = 'indicateur-editer-abonnement';
    const DETRUIRE      = 'indicateur-detruire-abonnement';
}