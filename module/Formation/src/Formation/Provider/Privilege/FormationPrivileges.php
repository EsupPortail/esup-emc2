<?php

namespace Formation\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FormationPrivileges extends Privileges
{
    const FORMATION_ACCES = 'Formation-Formation_acces';
    const FORMATION_AFFICHER = 'Formation-Formation_afficher';
    const FORMATION_AJOUTER = 'Formation-Formation_ajouter';
    const FORMATION_MODIFIER = 'Formation-Formation_modifier';
    const FORMATION_HISTORISER = 'Formation-Formation_historiser';
    const FORMATION_SUPPRIMER = 'Formation-Formation_supprimer';

    const FORMATION_QUESTIONNAIRE_VISUALISER = 'Formation-Formation_questionnaire_visualiser';
    const FORMATION_QUESTIONNAIRE_MODIFIER = 'Formation-Formation_questionnaire_modifier';
}