<?php

namespace Formation\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FormationenquetePrivileges extends Privileges
{
    const ENQUETE_INDEX = 'formationenquete-enquete_index';
    const ENQUETE_AJOUTER = 'formationenquete-enquete_ajouter';
    const ENQUETE_MODIFIER = 'formationenquete-enquete_modifier';
    const ENQUETE_HISTORISER = 'formationenquete-enquete_historiser';
    const ENQUETE_SUPPRIMER = 'formationenquete-enquete_supprimer';

    const ENQUETE_RESULTAT = 'formationenquete-enquete_resultat';
    const ENQUETE_REPONDRE = 'formationenquete-enquete_repondre';
}