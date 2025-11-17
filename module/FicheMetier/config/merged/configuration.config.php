<?php

namespace FichePoste;

use FicheMetier\Controller\ConfigurationController;
use FicheMetier\Controller\ConfigurationControllerFactory;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ConfigurationController::class,
                    'action' => [
                        'competence',
                        'application',
                        'ajouter',
                        'retirer',
                        'reappliquer'
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_INDEX, // todo privilege
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'fiche-metier' => [
                'child_routes' => [
                    'configuration' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/configuration',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'competence' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/competence',
                                    'defaults' => [
                                        /** @see ConfigurationController::competenceAction() */
                                        'action' => 'competence',
                                    ],
                                ],
                            ],
                            'application' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/application',
                                    'defaults' => [
                                        /** @see ConfigurationController::applicationAction() */
                                        'action' => 'application',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter/:type',
                                    'defaults' => [
                                        /** @see ConfigurationController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'retirer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/retirer/:id',
                                    'defaults' => [
                                        /** @see ConfigurationController::retirerAction() */
                                        'action' => 'retirer',
                                    ],
                                ],
                            ],
                            'reappliquer' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/reappliquer',
                                    'defaults' => [
                                        /** @see ConfigurationController::reappliquerAction() */
                                        'action' => 'reappliquer',
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
        ],
    ],
    'controllers' => [
        'factories' => [
            ConfigurationController::class => ConfigurationControllerFactory::class,
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