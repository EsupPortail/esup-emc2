<?php

namespace FichePoste;

use FichePoste\Controller\FichePosteController;
use FichePoste\Controller\ValidationController;
use FichePoste\Controller\ValidationControllerFactory;
use FichePoste\Provider\Privilege\FichePostePrivileges;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ValidationController::class,
                    'action' => [
                        'valider-responsable',
                        'revoquer-responsable',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_VALIDER_RESPONSABLE,
                ],
                [
                    'controller' => ValidationController::class,
                    'action' => [
                        'valider-agent',
                        'revoquer-agent',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_VALIDER_AGENT,
                ],
                [
                    'controller' => ValidationController::class,
                    'action' => [
                        'valider-drh',
                        'revoquer-drh',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_VALIDER_DRH,
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'fiche-poste' => [
                'type' => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route' => '/fiche-poste',
                    'defaults' => [
                        'controller' => FichePosteController::class,
                        'action' => 'index',
                    ],
                ],
                'child_routes' => [
                    'validation' => [
                        'type' => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route' => '/validation',
                            'defaults' => [
                                'controller' => ValidationController::class,
                            ],
                        ],
                        'child_routes' => [
                            'valider-responsable' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/valider-responsable/:fiche-poste',
                                    'defaults' => [
                                        /** @see ValidationController::validerResponsableAction() */
                                        'action' => 'valider-responsable',
                                    ],
                                ],
                            ],
                            'revoquer-responsable' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/revoquer-responsable/:fiche-poste',
                                    'defaults' => [
                                        /** @see ValidationController::revoquerResponsableAction() */
                                        'action' => 'revoquer-responsable',
                                    ],
                                ],
                            ],
                            'valider-agent' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/valider-agent/:fiche-poste',
                                    'defaults' => [
                                        /** @see ValidationController::validerAgentAction() */
                                        'action' => 'valider-agent',
                                    ],
                                ],
                            ],
                            'revoquer-agent' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/revoquer-agent/:fiche-poste',
                                    'defaults' => [
                                        /** @see ValidationController::revoquerAgentAction() */
                                        'action' => 'revoquer-agent',
                                    ],
                                ],
                            ],
                            'valider-drh' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/valider-drh/:fiche-poste',
                                    'defaults' => [
                                        /** @see ValidationController::validerDrhAction() */
                                        'action' => 'valider-drh',
                                    ],
                                ],
                            ],
                            'revoquer-drh' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/revoquer-drh/:fiche-poste',
                                    'defaults' => [
                                        /** @see ValidationController::revoquerDrhAction() */
                                        'action' => 'revoquer-drh',
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
    'controllers' => [
        'factories' => [
            ValidationController::class => ValidationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];