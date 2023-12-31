<?php

namespace Formation\Provider\Event;

class EvenementProvider {

    const NOTIFICATION_FORMATION_OUVERTE    = 'notification_nouvelle_session';
    const RAPPEL_FORMATION_AGENT_AVANT      = 'notification_rappel_session_imminente';
    const INSCRIPTION_CLOTURE               = 'cloture_automatique_inscription';
    const CONVOCATION                       = 'convocation_automatique';
    const DEMANDE_RETOUR                    = 'formation_demande_retour';
    const SESSION_CLOTURE                   = 'formation_session_cloture';
}