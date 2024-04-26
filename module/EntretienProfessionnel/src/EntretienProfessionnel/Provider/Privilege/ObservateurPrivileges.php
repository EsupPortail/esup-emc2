<?php

namespace EntretienProfessionnel\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class ObservateurPrivileges extends Privileges
{
    const OBSERVATEUR_INDEX = 'observateur-observateur_index';
    const OBSERVATEUR_INDEX_OBSERVATEUR = 'observateur-observateur_index_observateur';
    const OBSERVATEUR_AFFICHER = 'observateur-observateur_afficher';
    const OBSERVATEUR_AJOUTER = 'observateur-observateur_ajouter';
    const OBSERVATEUR_MODIFIER = 'observateur-observateur_modifier';
    const OBSERVATEUR_HISTORISER = 'observateur-observateur_historiser';
    const OBSERVATEUR_SUPPRIMER = 'observateur-observateur_supprimer';
    const OBSERVATEUR_RECHERCHER = 'observateur-observateur_rechercher';
}
