<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FicheMetierPrivileges extends Privileges
{
    const FICHEMETIER_INDEX = 'fichemetier-fichemetier_index';
    const AFFICHER      = 'fichemetier-afficher';
    const HISTORISER    = 'fichemetier-historiser';
    const EDITER        = 'fichemetier-editer-fiche';
    const AJOUTER       = 'fichemetier-ajouter-fiche';
    const VERIFIER      = 'fichemetier-verifier-fiche';
}
