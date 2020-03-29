<?php

namespace Application;

use Application\Controller\CorpsController;
use Application\Controller\CorpsControllerFactory;
use Application\Controller\MetierController;
use Application\Provider\Privilege\RessourceRhPrivileges;
use Application\Service\Corps\CorpsService;
use Application\Service\Corps\CorpsServiceFactory;
use Application\Service\Correspondance\CorrespondanceService;
use Application\Service\Correspondance\CorrespondanceServiceFactory;
use Application\Service\Grade\GradeService;
use Application\Service\Grade\GradeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CorpsController::class,
                    'action' => [
                        'index'
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AFFICHER,
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
                                'order' => 600,
                                'label' => 'Corps, grades et correspondances',
                                'route' => 'corps',
                                'resource' => PrivilegeController::getResourceId(CorpsController::class, 'index') ,
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
            'corps' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/corps',
                    'defaults' => [
                        'controller' => CorpsController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            CorpsService::class => CorpsServiceFactory::class,
            CorrespondanceService::class => CorrespondanceServiceFactory::class,
            GradeService::class => GradeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CorpsController::class => CorpsControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];