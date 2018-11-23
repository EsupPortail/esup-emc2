<?php

namespace Application;

use Application\Controller\RessourceRh\RessourceRhController;
use Application\Controller\RessourceRh\RessourceRhControllerFactory;
use Application\Form\RessourceRh\AgentStatusForm;
use Application\Form\RessourceRh\AgentStatusFormFactory;
use Application\Form\RessourceRh\AgentStatusHydrator;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\RessourceRh\RessourceRhServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'index',
                        'creer-agent-status',
                        'modifier-agent-status',
                        'effacer-agent-status',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'ressource-rh' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/ressource-rh',
                    'defaults' => [
                        'controller' => RessourceRhController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'creer-agent-status' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer-agent-status',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'creer-agent-status',
                            ],
                        ],
                    ],
                    'modifier-agent-status' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-agent-status/:id',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'modifier-agent-status',
                            ],
                        ],
                    ],
                    'effacer-agent-status' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer-agent-status/:id',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'effacer-agent-status',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
            RessourceRhService::class => RessourceRhServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            RessourceRhController::class => RessourceRhControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentStatusForm::class => AgentStatusFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokable' => [
            AgentStatusHydrator::class => AgentStatusHydrator::class,
        ]
    ]

];