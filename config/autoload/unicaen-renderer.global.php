<?php

use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenRenderer\Controller\MacroController;
use UnicaenRenderer\Controller\RenduController;
use UnicaenRenderer\Controller\TemplateController;

return [
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'renderer-header' => [
                                'label' => 'Gestion des contenus',
                                'route' => 'contenu',
                                'resources' => [
                                    PrivilegeController::getResourceId(MacroController::class, 'index'),
                                    PrivilegeController::getResourceId(TemplateController::class, 'index'),
                                    PrivilegeController::getResourceId(RenduController::class, 'index'),
                                ],
                                'order'    => 10000,
                                'dropdown-header' => true,
                            ],
                            'pages' => [
                                'macro' => [
                                    'label' => 'Macros',
                                    'route' => 'contenu/macro',
                                    'resource' => PrivilegeController::getResourceId(MacroController::class, 'index'),
                                    'order'    => 10100,
                                    'icon' => 'fas fa-angle-right',
                                ],
                                'template' => [
                                    'label' => 'Templates',
                                    'route' => 'contenu/template',
                                    'resource' => PrivilegeController::getResourceId(TemplateController::class, 'index'),
                                    'order'    => 10200,
                                    'icon' => 'fas fa-angle-right',
                                ],
                                'rendu' => [
                                    'label' => 'Contenus',
                                    'route' => 'contenu/rendu',
                                    'resource' => PrivilegeController::getResourceId(RenduController::class, 'index'),
                                    'order'    => 10300,
                                    'icon' => 'fas fa-angle-right',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];