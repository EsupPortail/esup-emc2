<?php

namespace Application;

use Application\Controller\AgentHierarchieController;
use Application\Controller\AgentHierarchieControllerFactory;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationForm;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationFormFactory;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationHydrator;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentAutorite\AgentAutoriteServiceFactory;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\AgentSuperieur\AgentSuperieurServiceFactory;
use Application\View\Helper\AgentAutoriteViewHelper;
use Application\View\Helper\AgentSuperieurViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'importer',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_INDEX, //todo "Faites mieux !!!"
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'agent' => [
                'child_routes' => [
                    'hierarchie' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/hierarchie',
                            'defaults' => [
                                /** @see AgentHierarchieController::indexAction() */
                                'controller' => AgentHierarchieController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:agent',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::afficherAction(): */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'importer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/importer',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::importerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'importer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            AgentAutoriteService::class => AgentAutoriteServiceFactory::class,
            AgentSuperieurService::class => AgentSuperieurServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AgentHierarchieController::class => AgentHierarchieControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentHierarchieImportationForm::class => AgentHierarchieImportationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentHierarchieImportationHydrator::class => AgentHierarchieImportationHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'agentAutorite' => AgentAutoriteViewHelper::class,
            'agentSuperieur' => AgentSuperieurViewHelper::class,
        ],
    ]

];