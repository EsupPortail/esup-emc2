<?php

namespace MissionSpecifique;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Observation\Controller\ObservationTypeController;
use Observation\Controller\ObservationTypeControllerFactory;
use Observation\Form\ObservationType\ObservationTypeForm;
use Observation\Form\ObservationType\ObservationTypeFormFactory;
use Observation\Form\ObservationType\ObservationTypeHydrator;
use Observation\Form\ObservationType\ObservationTypeHydratorFactory;
use Observation\Provider\Privilege\ObservationtypePrivileges;
use Observation\Service\ObservationType\ObservationTypeService;
use Observation\Service\ObservationType\ObservationTypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ObservationTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ObservationtypePrivileges::OBSERVATIONTYPE_INDEX,
                    ],
                ],
                [
                    'controller' => ObservationTypeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ObservationtypePrivileges::OBSERVATIONTYPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => ObservationTypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ObservationtypePrivileges::OBSERVATIONTYPE_AJOUTER,
                    ],
                ],
                [
                    'controller' => ObservationTypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ObservationtypePrivileges::OBSERVATIONTYPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => ObservationTypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ObservationtypePrivileges::OBSERVATIONTYPE_HISTORISER,
                    ],
                ],
                [
                    'controller' => ObservationTypeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ObservationtypePrivileges::OBSERVATIONTYPE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'observation' => [
                                'label' => "Gestion des types d'observations",
                                'route' => 'observation/otype',
                                'resource' => PrivilegeController::getResourceId(ObservationTypeController::class, 'index'),
                                'order' => 1000,
                                'dropdown-header' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'observation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/observation',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'otype' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/type',
                            'defaults' => [
                                /** @see ObservationTypeController::indexAction() */
                                'controller' => ObservationTypeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:observation-type',
                                    'defaults' => [
                                        /** @see ObservationTypeController::afficherAction() */
                                        'action'     => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        /** @see ObservationTypeController::ajouterAction() */
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:observation-type',
                                    'defaults' => [
                                        /** @see ObservationTypeController::modifierAction() */
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:observation-type',
                                    'defaults' => [
                                        /** @see ObservationTypeController::historiserAction() */
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:observation-type',
                                    'defaults' => [
                                        /** @see ObservationTypeController::restaurerAction() */
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:observation-type',
                                    'defaults' => [
                                        /** @see ObservationTypeController::supprimerAction() */
                                        'action'     => 'supprimer',
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
            ObservationTypeService::class => ObservationTypeServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            ObservationTypeController::class => ObservationTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ObservationTypeForm::class => ObservationTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ObservationTypeHydrator::class => ObservationTypeHydratorFactory::class,
        ],
    ]

];