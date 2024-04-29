<?php

namespace Formation\Provider\Etat;

class SessionEtats {

    const TYPE = "FORMATION_SESSION";

    const ETAT_CREATION_EN_COURS      = 'EN_CREATION';
    const ETAT_INSCRIPTION_OUVERTE    = 'INSCRIPTION_OUVERTE';
    const ETAT_INSCRIPTION_FERMEE     = 'INSCRIPTION_FERMEE';
    const ETAT_FORMATION_CONVOCATION  = 'CONVOCATION';
    const ETAT_ATTENTE_RETOURS        = 'ATTENTE_RETOUR';
    const ETAT_CLOTURE_INSTANCE       = 'FERMEE';
    const ETAT_SESSION_ANNULEE        = 'SESSION_ANNULEE';

    const ETATS_OUVERTS               = [
        SessionEtats::ETAT_CREATION_EN_COURS,
        SessionEtats::ETAT_INSCRIPTION_OUVERTE,
        SessionEtats::ETAT_INSCRIPTION_FERMEE,
        SessionEtats::ETAT_FORMATION_CONVOCATION,
        SessionEtats::ETAT_ATTENTE_RETOURS,
    ];

    const ETATS_PREPARATION         = [
        SessionEtats::ETAT_CREATION_EN_COURS,
        SessionEtats::ETAT_INSCRIPTION_OUVERTE,
        SessionEtats::ETAT_INSCRIPTION_FERMEE,
    ];

    const ETATS_FINAUX              = [
        SessionEtats::ETAT_SESSION_ANNULEE,
        SessionEtats::ETAT_CLOTURE_INSTANCE,
    ];
}