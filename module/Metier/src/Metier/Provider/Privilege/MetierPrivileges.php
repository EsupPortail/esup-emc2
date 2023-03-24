<?php

namespace Metier\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class MetierPrivileges extends Privileges
{
    const METIER_INDEX = 'metier-metier_index';
    const METIER_AFFICHER = 'metier-metier_afficher';
    const METIER_AJOUTER = 'metier-metier_ajouter';
    const METIER_MODIFIER = 'metier-metier_modifier';
    const METIER_HISTORISER = 'metier-metier_historiser';
    const METIER_SUPPRIMER = 'metier-metier_supprimer';
    const METIER_CARTOGRAPHIE = 'metier-metier_cartographie';
    const METIER_LISTER_AGENTS = 'metier-metier_lister_agents';
}