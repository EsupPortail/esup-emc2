<?php

namespace UnicaenParametre;

use UnicaenParametre\Controller\CategorieController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'parametre' => [
                                'label' => 'Paramètres',
                                'route' => 'parametre/index',
                                'resource' => PrivilegeController::getResourceId(CategorieController::class, 'index'),
                                'order' => 7020,
                                'pages' => [],
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];