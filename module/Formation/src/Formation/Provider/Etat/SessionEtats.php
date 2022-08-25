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
}