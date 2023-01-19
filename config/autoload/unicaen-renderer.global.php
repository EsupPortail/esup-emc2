<?php

use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenRenderer\Controller\TemplateController;

return [
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'template' => [
                                'label' => 'Templates',
                                'route' => 'contenu/template',
                                'resource' => PrivilegeController::getResourceId(TemplateController::class, 'index'),
                                'order'    => 10200,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];