<?php

namespace Agent\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class PortfolioPrivileges extends Privileges
{
    const PORTFOLIO_AFFICHER = 'portfolio-portfolio_afficher';
    const PORTFOLIO_AFFICHER_DOCUMENT = 'portfolio-portfolio_afficher_document';
    const PORTFOLIO_AJOUTER_DOCUMENT = 'portfolio-portfolio_ajouter_document';
    const PORTFOLIO_HISTORISER_DOCUMENT = 'portfolio-portfolio_historiser_document';
    const PORTFOLIO_RESTAURER_DOCUMENT = 'portfolio-portfolio_restaurer_document';
    const PORTFOLIO_SUPPRIMER_DOCUMENT = 'portfolio-portfolio_supprimer_document';
}
