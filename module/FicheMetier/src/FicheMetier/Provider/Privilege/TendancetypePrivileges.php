<?php

namespace FicheMetier\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class TendancetypePrivileges extends Privileges {

    const TENDANCETYPE_INDEX = 'tendancetype-tendancetype_index';
    const TENDANCETYPE_AFFICHER = 'tendancetype-tendancetype_afficher';
    const TENDANCETYPE_AJOUTER = 'tendancetype-tendancetype_ajouter';
    const TENDANCETYPE_MODIFIER = 'tendancetype-tendancetype_modifier';
    const TENDANCETYPE_HISTORISER = 'tendancetype-tendancetype_historiser';
    const TENDANCETYPE_SUPPRIMER = 'tendancetype-tendancetype_supprimer';
}