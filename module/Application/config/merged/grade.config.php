<?php

namespace Application;

use Application\Controller\GradeController;
use Application\Controller\GradeControllerFactory;
use Application\Provider\Privilege\CorpsPrivileges;
use Application\Service\Grade\GradeService;
use Application\Service\Grade\GradeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => GradeController::class,
                    'action' => [
                        'index',
                        'afficher-agents',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_INDEX,
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
                                'order' => 830,
                                'label' => 'Grades',
                                'route' => 'grade',
                                'resource' => PrivilegeController::getResourceId(GradeController::class, 'index') ,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'grade' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/grade',
                    'defaults' => [
                        'controller' => GradeController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher-agents' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agents/:grade',
                            'defaults' => [
                                'controller' => GradeController::class,
                                'action'     => 'afficher-agents',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            GradeService::class => GradeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            GradeController::class => GradeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ],
    'view_helpers' => [
        'factories' => [
        ],
    ],

];