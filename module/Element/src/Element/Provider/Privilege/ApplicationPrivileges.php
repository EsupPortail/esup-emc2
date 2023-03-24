<?php

namespace Element\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ApplicationPrivileges extends Privileges
{
    const APPLICATION_INDEX = 'application-application_index';
    const APPLICATION_AFFICHER = 'application-application_afficher';
    const APPLICATION_AJOUTER = 'application-application_ajouter';
    const APPLICATION_MODIFIER = 'application-application_modifier';
    const APPLICATION_HISTORISER = 'application-application_historiser';
    const APPLICATION_EFFACER = 'application-application_effacer';
    const APPLICATION_CARTOGRAPHIE = 'application-application_cartographie';
}