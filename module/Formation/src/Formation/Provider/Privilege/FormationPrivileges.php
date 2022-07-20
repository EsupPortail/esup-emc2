<?php

namespace Formation\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FormationPrivileges extends Privileges
{
    const FORMATION_ACCES = 'formation-formation_acces';
    const FORMATION_AFFICHER = 'formation-formation_afficher';
    const FORMATION_AJOUTER = 'formation-formation_ajouter';
    const FORMATION_MODIFIER = 'formation-formation_modifier';
    const FORMATION_HISTORISER = 'formation-formation_historiser';
    const FORMATION_SUPPRIMER = 'formation-formation_supprimer';

    const FORMATION_QUESTIONNAIRE_VISUALISER = 'formation-formation_questionnaire_visualiser';
    const FORMATION_QUESTIONNAIRE_MODIFIER = 'formation-formation_questionnaire_modifier';
}