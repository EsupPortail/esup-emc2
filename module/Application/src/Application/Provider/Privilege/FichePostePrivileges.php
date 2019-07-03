<?php

namespace Application\Provider\Privilege;

class FichePostePrivileges extends \UnicaenAuth\Provider\Privilege\Privileges
{
    const AFFICHER      = 'ficheposte-fp-afficher';
    const HISTORISER    = 'ficheposte-fp-historiser';
    const EDITER        = 'ficheposte-fp-editer-fiche';
    const AJOUTER       = 'ficheposte-fp-ajouter-fiche';
}
