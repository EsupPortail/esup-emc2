<?php

namespace UnicaenGlossaire\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class DefinitionPrivileges extends Privileges
{
    const DEFINITION_INDEX = 'definition-definition_index';
    const DEFINITION_AFFICHER = 'definition-definition_afficher';
    const DEFINITION_AJOUTER = 'definition-definition_ajouter';
    const DEFINITION_MODIFIER = 'definition-definition_modifier';
    const DEFINITION_HISTORISER = 'definition-definition_historiser';
    const DEFINITION_SUPPRIMER = 'definition-definition_supprimer';
}