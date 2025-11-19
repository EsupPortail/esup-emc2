<?php

namespace FicheMetier\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ActivitePrivileges extends Privileges
{
    const ACTIVITE_INDEX = 'activite-activite_index';
    const ACTIVITE_AFFICHER = 'activite-activite_afficher';
    const ACTIVITE_AJOUTER = 'activite-activite_ajouter';
    const ACTIVITE_MODIFIER = 'activite-activite_modifier';
    const ACTIVITE_HISTORISER = 'activite-activite_historiser';
    const ACTIVITE_SUPPRIMER = 'activite-activite_supprimer';
    const ACTIVITE_RECHERCHER = 'activite-activite_rechercher';
    const ACTIVITE_IMPORTER = 'activite-activite_importer';
}