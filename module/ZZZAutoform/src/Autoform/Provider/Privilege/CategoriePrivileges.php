<?php

namespace Autoform\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class CategoriePrivileges extends Privileges
{
    const AFFICHER    = 'autoform-afficher-categorie';
    const CREER       = 'autoform-creer-categorie';
    const MODIFIER    = 'autoform-modifier-categorie';
    const HISTORISER  = 'autoform-historiser-categorie';
    const DETRUIRE    = 'autoform-detruire-categorie';
}