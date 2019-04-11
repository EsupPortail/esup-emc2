<?php

namespace Application;

use Application\Controller\Agent\AgentController;
use Application\Controller\Agent\AgentControllerFactory;
use Application\Form\Agent\AgentForm;
use Application\Form\Agent\AgentFormFactory;
use Application\Form\Agent\AgentHydrator;
use Application\Form\Agent\AgentHydratorFactory;
use Application\Form\Agent\AgentImportForm;
use Application\Form\Agent\AgentImportFormFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Agent\AgentService;
use Application\Service\Agent\AgentServiceFactory;
use Application\View\Helper\AgentViewHelper;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [
                        AgentPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'ajouter',
                        'importer',
                        'rechercher-individu',
                    ],
                    'privileges' => [
                        AgentPrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        AgentPrivileges::EFFACER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        AgentPrivileges::EDITER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'agent' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/agent',
                    'defaults' => [
                        'controller' => AgentController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:id',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:id',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:id',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'importer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/importer',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'importer',
                            ],
                        ],
                    ],
                    'rechercher-individu' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher-individu',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'rechercher-individu',
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
            AgentService::class => AgentServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AgentController::class => AgentControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentForm::class => AgentFormFactory::class,
            AgentImportForm::class => AgentImportFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentHydrator::class => AgentHydratorFactory::class,
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'agent' => AgentViewHelper::class,
        ],
    ],


];