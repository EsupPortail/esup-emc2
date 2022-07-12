<?php

namespace Element;

use Element\Controller\CompetenceImporterController;
use Element\Controller\CompetenceImporterControllerFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CompetenceImporterController::class,
                    'action' => [
                        'importer',
                    ],
                    'role' => [
                        'Administrateur·trice technique',
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'competence-import' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/competence-import',
                    'defaults' => [
                        'controller' => CompetenceImporterController::class,
                        'action' => 'importer',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [
            CompetenceImporterController::class => CompetenceImporterControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];