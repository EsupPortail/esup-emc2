<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FichePostePrivileges extends Privileges
{
    const AFFICHER      = 'ficheposte-fp-afficher';
    const HISTORISER    = 'ficheposte-fp-historiser';
    const DETRUIRE      = 'ficheposte-fp-detruire';
    const EDITER        = 'ficheposte-fp-editer-fiche';
    const AJOUTER       = 'ficheposte-fp-ajouter-fiche';
}
