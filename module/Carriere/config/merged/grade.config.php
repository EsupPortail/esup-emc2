<?php

namespace Carriere;

use Carriere\Controller\GradeController;
use Carriere\Controller\GradeControllerFactory;
use Carriere\Provider\Privilege\GradePrivileges;
use Carriere\Service\Grade\GradeService;
use Carriere\Service\Grade\GradeServiceFactory;
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
                        GradePrivileges::GRADE_INDEX
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
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];