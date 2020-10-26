<?php

namespace Formation\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FormationPrivileges extends Privileges
{
    const FORMATION_INDEX = 'formation-formation_index';
    const FORMATION_AFFICHER = 'formation-formation_afficher';
    const FORMATION_DETRUIRE = 'formation-formation_detruire';
    const FORMATION_EDITER = 'formation-formation_editer';
    const FORMATION_AJOUTER = 'formation-formation_ajouter';
    const FORMATION_HISTORISER = 'formation-formation_historiser';

    const FORMATION_INSTANCE_AFFICHER = 'formation-formation_instance_afficher';
    const FORMATION_INSTANCE_AJOUTER = 'formation-formation_instance_ajouter';
    const FORMATION_INSTANCE_MODIFIER = 'formation-formation_instance_modifier';
    const FORMATION_INSTANCE_HISTORISER = 'formation-formation_instance_historiser';
    const FORMATION_INSTANCE_SUPPRIMER = 'formation-formation_instance_supprimer';
    const FORMATION_INSTANCE_QUESTIONNAIRE_VISUALISER = 'formation-formation_instance_questionnaire_visualiser';
    const FORMATION_INSTANCE_QUESTIONNAIRE_RENSEIGNER = 'formation-formation_instance_questionnaire_renseigner';
}