<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\ObservationController;
use EntretienProfessionnel\Controller\ObservationControllerFactory;
use EntretienProfessionnel\Provider\Privilege\ObservationPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ObservationController::class,
                    'action' => [
                        'ajouter',
                        'ajouter-agent',
                        'ajouter-autorite',
                    ],
                    'privileges' => [
                        ObservationPrivileges::OBSERVATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => ObservationController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ObservationPrivileges::OBSERVATION_MODIFIER,
                    ],
                ],
                [
                    'controller' => ObservationController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ObservationPrivileges::OBSERVATION_HISTORISER,
                    ],
                ],
                [
                    'controller' => ObservationController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ObservationPrivileges::OBSERVATION_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'entretien-professionnel' => [
                'child_routes' => [
                    'observation' => [
                        'type'  => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/observation',
                        ],
                        'child_routes' => [
                            'agent' => [
                                'type'  => Literal::class,
                                'may_terminate' => false,
                                'options' => [
                                    'route'    => '/agent',
                                ],
                                'child_routes' => [
                                    'ajouter' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/ajouter/:entretien-professionnel/:type',
                                            'defaults' => [
                                                /** @see ObservationController::ajouterAgentAction() */
                                                'controller' => ObservationController::class,
                                                'action'     => 'ajouter-agent',
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'autorite' => [
                                'type'  => Literal::class,
                                'may_terminate' => false,
                                'options' => [
                                    'route'    => '/autorite',
                                ],
                                'child_routes' => [
                                    'ajouter' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/ajouter/:entretien-professionnel',
                                            'defaults' => [
                                                /** @see ObservationController::ajouterAutoriteAction() */
                                                'controller' => ObservationController::class,
                                                'action'     => 'ajouter-autorite',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/ajouter/:entretien-professionnel',
                                    'defaults' => [
                                        /** @see ObservationController::ajouterAction() */
                                        'controller' => ObservationController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier/:observation',
                                    'defaults' => [
                                        /** @see ObservationController::modifierAction() */
                                        'controller' => ObservationController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser/:observation',
                                    'defaults' => [
                                        /** @see ObservationController::historiserAction() */
                                        'controller' => ObservationController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer/:observation',
                                    'defaults' => [
                                        /** @see ObservationController::restaurerAction() */
                                        'controller' => ObservationController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/supprimer/:observation',
                                    'defaults' => [
                                        /** @see ObservationController::supprimerAction() */
                                        'controller' => ObservationController::class,
                                        'action'     => 'supprimer',
                                    ],
                                ],
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            ObservationController::class => ObservationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ]

];