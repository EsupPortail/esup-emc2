<?php

use UnicaenEtat\Controller\EtatTypeController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'unicaen-etat' => [
                                'label' => 'État',
                                'route' => 'unicaen-etat/etat-type',
                                'resource' => PrivilegeController::getResourceId(EtatTypeController::class, 'index'),
                                'order'    => 5000,
                                'icon' => 'fas fa-angle-right',
                                'pages' => [
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];

?>