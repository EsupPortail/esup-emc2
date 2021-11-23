<?php

namespace Application;

use Application\Controller\CorpsController;
use Application\Controller\CorpsControllerFactory;
use Application\Form\ModifierNiveau\ModifierNiveauForm;
use Application\Form\ModifierNiveau\ModifierNiveauFormFactory;
use Application\Form\ModifierNiveau\ModifierNiveauHydrator;
use Application\Form\ModifierNiveau\ModifierNiveauHydratorFactory;
use Application\Provider\Privilege\CorpsPrivileges;
use Application\Service\Corps\CorpsService;
use Application\Service\Corps\CorpsServiceFactory;
use Application\Service\Correspondance\CorrespondanceService;
use Application\Service\Correspondance\CorrespondanceServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CorpsController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_INDEX,
                    ],
                ],
                [
                    'controller' => CorpsController::class,
                    'action' => [
                        'afficher-agents',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_AFFICHER,
                    ],
                ],
                [
                    'controller' => CorpsController::class,
                    'action' => [
                        'modifier-niveau',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_MODIFIER,
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
                                'order' => 800,
                                'label' => 'Gestion des corps et grades',
                                'route' => 'corps',
                                'resource' => PrivilegeController::getResourceId(CorpsController::class, 'index') ,
                                'dropdown-header' => true,
                            ],
                            [
                                'order' => 820,
                                'label' => 'Corps',
                                'route' => 'corps',
                                'resource' => PrivilegeController::getResourceId(CorpsController::class, 'index') ,
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
                'child_routes' => [
                    'afficher-agents' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agents/:corps',
                            'defaults' => [
                                'controller' => CorpsController::class,
                                'action'     => 'afficher-agents',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-niveau' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-niveau/:corps',
                            'defaults' => [
                                'controller' => CorpsController::class,
                                'action'     => 'modifier-niveau',
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
            CorpsService::class => CorpsServiceFactory::class,
            CorrespondanceService::class => CorrespondanceServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CorpsController::class => CorpsControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ModifierNiveauForm::class => ModifierNiveauFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ModifierNiveauHydrator::class => ModifierNiveauHydratorFactory::class,
        ],
    ]

];