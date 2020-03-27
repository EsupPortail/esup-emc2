<?php

namespace Mailing\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

/**
 * Liste des privilèges utilisables.
 */
class MailingPrivileges extends Privileges
{
    const MAILING_AFFICHER                         = 'mailing-mailing_afficher';
    const MAILING_TEST                             = 'mailing-mailing_test';
    const MAILING_ENVOI                            = 'mailing-mailing_envoi';
    const MAILING_REENVOI                          = 'mailing-mailing_reenvoi';
    const MAILING_EFFACER                          = 'mailing-mailing_effacer';

}