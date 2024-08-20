<?php

namespace Formation;

use Formation\Controller\FormationInstanceDocumentController;
use Formation\Controller\FormationInstanceDocumentControllerFactory;
use Formation\Provider\Privilege\FormationinstancedocumentPrivileges;
use Laminas\Router\Http\Literal;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstanceDocumentController::class,
                    'action' => [
                        'generer-convocation',
                    ],
                    'privileges' => [
                        FormationinstancedocumentPrivileges::FORMATIONINSTANCEDOCUMENT_CONVOCATION
                    ],
                ],
                [
                    'controller' => FormationInstanceDocumentController::class,
                    'action' => [
                        'generer-attestation',
                    ],
                    'privileges' => [
                        FormationinstancedocumentPrivileges::FORMATIONINSTANCEDOCUMENT_ATTESTATION
                    ],
                ],
                [
                    'controller' => FormationInstanceDocumentController::class,
                    'action' => [
                        'generer-absence',
                    ],
                    'privileges' => [
                        FormationinstancedocumentPrivileges::FORMATIONINSTANCEDOCUMENT_ABSENCE
                    ],
                ],
                [
                    'controller' => FormationInstanceDocumentController::class,
                    'action' => [
                        'generer-historique',
                    ],
                    'privileges' => [
                        FormationinstancedocumentPrivileges::FORMATIONINSTANCEDOCUMENT_HISTORIQUE
                    ],
                ],
                [
                    'controller' => FormationInstanceDocumentController::class,
                    'action' => [
                        'export-emargement',
                        'export-tous-emargements',
                    ],
                    'privileges' => [
                        FormationinstancedocumentPrivileges::FORMATIONINSTANCEDOCUMENT_EMARGEMENT
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/formation',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'session' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/session',
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                    'generer-convocation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/generer-convocation/:inscription',
                            'defaults' => [
                                'controller' => FormationInstanceDocumentController::class,
                                'action'     => 'generer-convocation',
                            ],
                        ],
                    ],
                    'generer-attestation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/generer-attestation/:inscription',
                            'defaults' => [
                                /** @see FormationInstanceDocumentController::genererAttestationAction() */
                                'controller' => FormationInstanceDocumentController::class,
                                'action'     => 'generer-attestation',
                            ],
                        ],
                    ],
                    'generer-absence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/generer-absence/:inscription',
                            'defaults' => [
                                /** @see FormationInstanceDocumentController::genererAbsenceAction() */
                                'controller' => FormationInstanceDocumentController::class,
                                'action'     => 'generer-absence',
                            ],
                        ],
                    ],
                    'generer-historique' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/generer-historique/:agent',
                            'defaults' => [
                                'controller' => FormationInstanceDocumentController::class,
                                'action'     => 'generer-historique',
                            ],
                        ],
                    ],
                    'export-emargement' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/export-emargement/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceDocumentController::class,
                                'action'     => 'export-emargement',
                            ],
                        ],
                    ],
                    'export-tous-emargements' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/export-tous-emargements/:session',
                            'defaults' => [
                                'controller' => FormationInstanceDocumentController::class,
                                'action'     => 'export-tous-emargements',
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
            FormationInstanceDocumentController::class => FormationInstanceDocumentControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];