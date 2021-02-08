<?php

namespace Metier\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ReferencemetierPrivileges extends Privileges
{
    const REFERENCE_INDEX = 'referenceMetier-reference_index';
    const REFERENCE_AFFICHER = 'referenceMetier-reference_afficher';
    const REFERENCE_AJOUTER = 'referenceMetier-reference_ajouter';
    const REFERENCE_MODIFIER = 'referenceMetier-reference_modifier';
    const REFERENCE_HISTORISER = 'referenceMetier-reference_historiser';
    const REFERENCE_SUPPRIMER = 'referenceMetier-reference_supprimer';
}