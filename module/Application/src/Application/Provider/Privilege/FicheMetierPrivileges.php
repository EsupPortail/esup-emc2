<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FicheMetierPrivileges extends Privileges
{
    const FICHEMETIER_INDEX         = 'fichemetier-fichemetier_index';
    const FICHEMETIER_AFFICHER      = 'fichemetier-fichemetier_afficher';
    const FICHEMETIER_AJOUTER       = 'fichemetier-fichemetier_ajouter';
    const FICHEMETIER_MODIFIER      = 'fichemetier-fichemetier_modifier';
    const FICHEMETIER_HISTORISER    = 'fichemetier-fichemetier_historiser';
    const FICHEMETIER_DETRUIRE      = 'fichemetier-fichemetier_detruire';
    const FICHEMETIER_ETAT          = 'fichemetier-fichemetier_etat';
}
