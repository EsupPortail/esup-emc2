<?php

namespace Formation\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FormationinstancePrivileges extends Privileges
{
    const FORMATIONINSTANCE_AFFICHER = 'FormationInstance-FormationInstance_afficher';
    const FORMATIONINSTANCE_AJOUTER = 'FormationInstance-FormationInstance_ajouter';
    const FORMATIONINSTANCE_MODIFIER = 'FormationInstance-FormationInstance_modifier';
    const FORMATIONINSTANCE_HISTORISER = 'FormationInstance-FormationInstance_historiser';
    const FORMATIONINSTANCE_SUPPRIMER = 'FormationInstance-FormationInstance_supprimer';
    const FORMATIONINSTANCE_GERER_INSCRIPTION = 'FormationInstance-FormationInstance_gerer_inscription';
}