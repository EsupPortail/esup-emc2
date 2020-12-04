<?php

namespace Application\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FicheprofilPrivileges extends Privileges
{
    const FICHEPROFIL_AFFICHER = 'ficheprofil-ficheprofil_afficher';
    const FICHEPROFIL_EXPORTER = 'ficheprofil-ficheprofil_exporter';
    const FICHEPROFIL_AJOUTER = 'ficheprofil-ficheprofil_ajouter';
    const FICHEPROFIL_MODIFIER = 'ficheprofil-ficheprofil_modifier';
    const FICHEPROFIL_HISTORISER = 'ficheprofil-ficheprofil_historiser';
    const FICHEPROFIL_SUPPRIMER = 'ficheprofil-ficheprofil_supprimer';
}