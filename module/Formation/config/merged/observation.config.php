<?php

namespace Formation;

use Formation\Controller\ObservationController;
use Formation\Controller\ObservationControllerFactory;
use Formation\Provider\Privilege\DemandeexternePrivileges;
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
                        'ajouter-demande-externe',
                    ],
                    'privileges' => [
//                        DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_GESTIONNAIRE,
//                        DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_RESPONSABLE,
//                        DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_DRH,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'formation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/formation',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'demande-externe' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/demande-externe',
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter-observation' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter-observation/:demande-externe',
                                    'defaults' => [
                                        /** @see ObservationController::ajouterDemandeExterneAction() */
                                        'controller' => ObservationController::class,
                                        'action' => 'ajouter-demande-externe',
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
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [
            ObservationController::class => ObservationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];