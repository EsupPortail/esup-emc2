<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class StructurePrivileges extends Privileges
{
    const AFFICHER              = 'structure-afficher-structure';
    const AJOUTER               = 'structure-ajouter-structure';
    const EDITER                = 'structure-editer-structure';
    const HISTORISER            = 'structure-historiser-structure';
    const EFFACER               = 'structure-effacer-structure';
    const GESTIONNAIRE          = 'structure-gerer-gestionnaire';
    const SYNCHRONISER          = 'structure-synchroniser-structure';
    const EDITER_DESCRIPTION    = 'structure-editer-description';
}
