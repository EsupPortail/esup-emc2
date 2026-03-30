<?php

use UnicaenAide\Controller\AdministrationController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'unicaenaide' => [
                                'label'    => "Administration de l'aide",
                                'route'    => "unicaen-aide/administration",
                                'resource' => PrivilegeController::getResourceId(AdministrationController::class, 'index'),
                                'order'    => 7080,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
