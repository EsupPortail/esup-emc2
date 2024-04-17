<?php

namespace Formation\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class FormationinstancePrivileges extends Privileges
{
    const FORMATIONINSTANCE_INDEX = 'formationinstance-formationinstance_index';
    const FORMATIONINSTANCE_AFFICHER = 'formationinstance-formationinstance_afficher';
    const FORMATIONINSTANCE_AJOUTER = 'formationinstance-formationinstance_ajouter';
    const FORMATIONINSTANCE_MODIFIER = 'formationinstance-formationinstance_modifier';
    const FORMATIONINSTANCE_HISTORISER = 'formationinstance-formationinstance_historiser';
    const FORMATIONINSTANCE_SUPPRIMER = 'formationinstance-formationinstance_supprimer';

    const FORMATIONINSTANCE_AFFICHER_INSCRIPTION = 'formationinstance-formationinstance_afficher_inscription';
    const FORMATIONINSTANCE_GERER_INSCRIPTION = 'formationinstance-formationinstance_gerer_inscription';
    const FORMATIONINSTANCE_GERER_SEANCE = 'formationinstance-formationinstance_gerer_seance';
    const FORMATIONINSTANCE_GERER_FORMATEUR = 'formationinstance-formationinstance_gerer_formateur';
    const FORMATIONINSTANCE_ANNULER =  'formationinstance-formationinstance_annuler';
}