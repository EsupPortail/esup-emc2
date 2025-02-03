<?php

use UnicaenContact\Controller\TypeController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'contact' => [
                                'label' => 'Contact',
                                'route' => 'unicaen-contact/type',
                                'resource' => PrivilegeController::getResourceId(TypeController::class, 'index'),
                                'order' => 4000,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];