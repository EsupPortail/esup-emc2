<?php

namespace Mailing\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

/**
 * Liste des privilèges utilisables.
 */
class MailingPrivileges extends Privileges
{
    const HISTORIQUE                               = 'mailing-historique-mailing';
    const AFFICHER                                 = 'mailing-afficher-mailing';
    const ENVOI_TEST                               = 'mailing-test-mailing';
    const ENVOI                                    = 'mailing-envoi-mailing';
    const RE_ENVOI                                 = 'mailing-reenvoi-mailing';
    const EFFACER                                  = 'mailing-effacer-mailing';

}