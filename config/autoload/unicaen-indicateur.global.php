<?php

use UnicaenIndicateur\Controller\IndexController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'mes-indicateurs' => [
                                'label' => 'Mes indicateurs',
                                'route' => 'mes-indicateurs',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
                                'order'    => 500,
                            ],
                        ],
                    ]
                ],
            ],
        ],
    ],
];