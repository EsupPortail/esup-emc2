<?php

namespace Application;

use Application\Controller\AgentAccompagnementController;
use Application\Controller\AgentAccompagnementControllerFactory;
use Application\Form\AgentAccompagnement\AgentAccompagnementForm;
use Application\Form\AgentAccompagnement\AgentAccompagnementFormFactory;
use Application\Form\AgentAccompagnement\AgentAccompagnementHydrator;
use Application\Form\AgentAccompagnement\AgentAccompagnementHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\AgentAccompagnement\AgentAccompagnementService;
use Application\Service\AgentAccompagnement\AgentAccompagnementServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentAccompagnementController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'detruire',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_GESTION_CCC,
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
                ],
                'child_routes' => [
                    'accompagnement' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/accompagnement',
                            'defaults' => [
                                'controller' => AgentAccompagnementController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent',
                                    'defaults' => [
                                        'controller' => AgentAccompagnementController::class,
                                        'action' => 'ajouter'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:accompagnement',
                                    'defaults' => [
                                        'controller' => AgentAccompagnementController::class,
                                        'action' => 'modifier'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:accompagnement',
                                    'defaults' => [
                                        'controller' => AgentAccompagnementController::class,
                                        'action' => 'historiser'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:accompagnement',
                                    'defaults' => [
                                        'controller' => AgentAccompagnementController::class,
                                        'action' => 'restaurer'
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:accompagnement',
                                    'defaults' => [
                                        'controller' => AgentAccompagnementController::class,
                                        'action' => 'detruire'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            AgentAccompagnementService::class => AgentAccompagnementServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AgentAccompagnementController::class => AgentAccompagnementControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentAccompagnementForm::class => AgentAccompagnementFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentAccompagnementHydrator::class => AgentAccompagnementHydratorFactory::class,
        ],
    ]

];