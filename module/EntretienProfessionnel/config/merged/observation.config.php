<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\ObservationController;
use EntretienProfessionnel\Controller\ObservationControllerFactory;
use EntretienProfessionnel\Form\Observation\ObservationForm;
use EntretienProfessionnel\Form\Observation\ObservationFormFactory;
use EntretienProfessionnel\Form\Observation\ObservationHydrator;
use EntretienProfessionnel\Form\Observation\ObservationHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\ObservationPrivileges;
use EntretienProfessionnel\Service\Observation\ObservationService;
use EntretienProfessionnel\Service\Observation\ObservationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ObservationController::class,
                    'action' => [
//                        'index',
//                        'afficher',
                    ],
                    'privileges' => [
                        ObservationPrivileges::OBSERVATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => ObservationController::class,
                    'action' => [
                        'ajouter',
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
                            'ajouter' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/ajouter/:entretien-professionnel',
                                    'defaults' => [
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
            ObservationService::class => ObservationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ObservationController::class => ObservationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ObservationForm::class => ObservationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ObservationHydrator::class => ObservationHydratorFactory::class,
        ],
    ]

];