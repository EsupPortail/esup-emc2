<?php

namespace Formation;

use Formation\Controller\AbonnementController;
use Formation\Controller\AbonnementControllerFactory;
use Formation\Provider\Privilege\FormationabonnementPrivileges;
use Formation\Service\Abonnement\AbonnementService;
use Formation\Service\Abonnement\AbonnementServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AbonnementController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FormationabonnementPrivileges::FORMATIONABONNEMENT_ABONNER,
                    ],
                ],
                [
                    'controller' => AbonnementController::class,
                    'action' => [
                        'retirer',
                    ],
                    'privileges' => [
                        FormationabonnementPrivileges::FORMATIONABONNEMENT_DESABONNER,
                    ],
                ],
                [
                    'controller' => AbonnementController::class,
                    'action' => [
                        'lister-abonnements-par-agent',
                    ],
                    'privileges' => [
                        FormationabonnementPrivileges::FORMATIONABONNEMENT_LISTE_AGENT,
                    ],
                ],
                [
                    'controller' => AbonnementController::class,
                    'action' => [
                        'lister-abonnements-par-formation',
                    ],
                    'privileges' => [
                        FormationabonnementPrivileges::FORMATIONABONNEMENT_LISTE_FORMATION,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation',
//                    'defaults' => [
//                        'controller' => FormationController::class,
//                        'action'     => 'index',
//                    ],
                ],
                'child_routes' => [
                    'abonnement' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/abonnement',
//                            'defaults' => [
//                                'controller' => AbonnementController::class,
//                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:formation',
                                    'defaults' => [
                                        'controller' => AbonnementController::class,
                                        'action' => 'ajouter'
                                    ],
                                ],
                            ],
                            'retirer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/retirer/:formation',
                                    'defaults' => [
                                        'controller' => AbonnementController::class,
                                        'action' => 'retirer'
                                    ],
                                ],
                            ],
                            'lister-abonnements-par-agent' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/lister-abonnements-par-agent/:agent',
                                    'defaults' => [
                                        'controller' => AbonnementController::class,
                                        'action' => 'lister-abonnements-par-agent'
                                    ],
                                ],
                            ],
                            'lister-abonnements-par-formation' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/lister-abonnements-par-formation/:formation',
                                    'defaults' => [
                                        'controller' => AbonnementController::class,
                                        'action' => 'lister-abonnements-par-formation'
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
            AbonnementService::class => AbonnementServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AbonnementController::class => AbonnementControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];