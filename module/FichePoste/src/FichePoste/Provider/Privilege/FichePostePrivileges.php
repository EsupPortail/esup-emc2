<?php

namespace FichePoste\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FichePostePrivileges extends Privileges
{
    const FICHEPOSTE_INDEX              = 'ficheposte-ficheposte_index';
    const FICHEPOSTE_AFFICHER           = 'ficheposte-ficheposte_afficher';
    const FICHEPOSTE_AJOUTER            = 'ficheposte-ficheposte_ajouter';
    const FICHEPOSTE_MODIFIER           = 'ficheposte-ficheposte_modifier';
    const FICHEPOSTE_HISTORISER         = 'ficheposte-ficheposte_historiser';
    const FICHEPOSTE_DETRUIRE           = 'ficheposte-ficheposte_detruire';

    const FICHEPOSTE_AFFICHER_POSTE     = 'ficheposte-ficheposte_afficher_poste';
    const FICHEPOSTE_MODIFIER_POSTE     = 'ficheposte-ficheposte_modifier_poste';

    const FICHEPOSTE_ASSOCIERAGENT      = 'ficheposte-ficheposte_associeragent';
    const FICHEPOSTE_GRAPHIQUE          = 'ficheposte-ficheposte_graphique';
    const FICHEPOSTE_ETAT               = 'ficheposte-ficheposte_etat';
    const FICHEPOSTE_VALIDER_RESPONSABLE = 'ficheposte-ficheposte_valider_responsable';
    const FICHEPOSTE_VALIDER_AGENT      = 'ficheposte-ficheposte_valider_agent';
}
