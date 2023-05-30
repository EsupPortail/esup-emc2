<?php

namespace Carriere;

use Carriere\Controller\EmploiTypeController;
use Carriere\Controller\EmploiTypeControllerFactory;
use Carriere\Provider\Privilege\CorpsPrivileges;
use Carriere\Service\EmploiType\EmploiTypeService;
use Carriere\Service\EmploiType\EmploiTypeServiceFactory;
use Carriere\View\Helper\EmploiTypeViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => EmploiTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_INDEX,
                    ],
                ],
                [
                    'controller' => EmploiTypeController::class,
                    'action' => [
                        'afficher-agents',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_LISTER_AGENTS,
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
                                'order' => 2200,
                                'label' => 'Emplois types',
                                'route' => 'emploi-type',
                                'resource' => PrivilegeController::getResourceId(EmploiTypeController::class, 'index') ,
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
            'emploi-type' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/emploi-type',
                    'defaults' => [
                        /** @see EmploiTypeController::indexAction() */
                        'controller' => EmploiTypeController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher-agents' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agents/:emploi-type',
                            'defaults' => [
                                /** @see CorpsController::afficherAgentsAction() */
                                'controller' => EmploiTypeController::class,
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
            EmploiTypeService::class => EmploiTypeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            EmploiTypeController::class => EmploiTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ],
    'view_helpers' => [
        'invokables' => [
            'emploitype' => EmploiTypeViewHelper::class
        ],
    ],

];