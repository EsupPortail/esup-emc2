<?php

namespace Metier\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class DomainePrivileges extends Privileges
{
    const DOMAINE_INDEX = 'domaine-domaine_index';
    const DOMAINE_AFFICHER = 'domaine-domaine_afficher';
    const DOMAINE_AJOUTER = 'domaine-domaine_ajouter';
    const DOMAINE_MODIFIER = 'domaine-domaine_modifier';
    const DOMAINE_HISTORISER = 'domaine-domaine_historiser';
    const DOMAINE_SUPPRIMER = 'domaine-domaine_supprimer';
}