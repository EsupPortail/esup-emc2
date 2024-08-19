<?php

namespace UnicaenEnquete;

use UnicaenEnquete\Controller\EnqueteController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'enquete' => [
                                'label' => "Enquêtes",
                                'route' => 'enquete/enquete',
                                'resource' => PrivilegeController::getResourceId(EnqueteController::class, 'index'),
                                'order' => 1000 + 900,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];