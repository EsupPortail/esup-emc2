<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FichePostePrivileges extends Privileges
{
    const FICHEPOSTE_AFFICHER   = 'ficheposte-ficheposte_afficher';
    const FICHEPOSTE_AJOUTER    = 'ficheposte-ficheposte_ajouter';
    const FICHEPOSTE_MODIFIER   = 'ficheposte-ficheposte_modifier';
    const FICHEPOSTE_HISTORISER = 'ficheposte-ficheposte_historiser';
    const FICHEPOSTE_DETRUIRE   = 'ficheposte-ficheposte_detruire';
}
