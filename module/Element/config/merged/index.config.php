<?php

namespace Element;

use Element\Controller\IndexController;
use Element\Controller\IndexControllerFactory;
use Element\Provider\Privilege\ApplicationPrivileges;
use Element\Provider\Privilege\CompetencePrivileges;
use Element\Provider\Privilege\NiveauPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_INDEX,
                        ApplicationPrivileges::APPLICATION_INDEX,
                        CompetencePrivileges::COMPETENCE_INDEX,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            [
                                'order' => 3000,
                                'label' => 'Autres ressources',
                                'route' => 'element',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index') ,
                                'dropdown-header' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'element' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/element',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
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
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];