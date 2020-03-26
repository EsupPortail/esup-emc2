<?php

use UnicaenUtilisateurLdapAdapter\Service\LdapService;
use UnicaenUtilisateurLdapAdapter\Service\LdapServiceFactory;

return [
    'service_manager' => [
        'factories' => [
            LdapService::class => LdapServiceFactory::class,
        ],
    ],
];