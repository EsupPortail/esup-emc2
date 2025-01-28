<?php

use Application\Service\Perimetre\PerimetreService;
use UnicaenIndicateur\Controller\IndexController;
use UnicaenIndicateur\Controller\IndicateurController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'indicateurs' => [
                                'label' => 'Indicateurs',
                                'route' => 'indicateurs',
                                'resource' => PrivilegeController::getResourceId(IndicateurController::class, 'index'),
                                'order' => 4000,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                    'mes-indicateurs' => [
                        'label' => 'Mes indicateurs',
                        'route' => 'mes-indicateurs',
                        'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
                        'order' => 10001,
                        'icon' => 'fas fa-angle-right',
                    ],
                ],
            ],
        ],
    ],

    'unicaen-indicateur' => [
        'perimetreService' => PerimetreService::class,

        'perimetres' => [
            'structure' => "Périmètre restraignant les résultats à respecter les structures d'affectation (perimetre_structure_id)",
            'role' => "Périmètre restraignant les résultats à respecter un rôle (perimetre_role_id)",
        ],
    ],
];