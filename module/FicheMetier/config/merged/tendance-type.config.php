<?php

namespace FichePoste;

use FicheMetier\Controller\TendanceTypeController;
use FicheMetier\Controller\TendanceTypeControllerFactory;
use FicheMetier\Form\TendanceType\TendanceTypeForm;
use FicheMetier\Form\TendanceType\TendanceTypeFormFactory;
use FicheMetier\Form\TendanceType\TendanceTypeHydrator;
use FicheMetier\Form\TendanceType\TendanceTypeHydratorFactory;
use FicheMetier\Provider\Privilege\TendancetypePrivileges;
use FicheMetier\Service\TendanceType\TendanceTypeService;
use FicheMetier\Service\TendanceType\TendanceTypeServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => TendanceTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        TendancetypePrivileges::TENDANCETYPE_INDEX,
                    ],
                ],
                [
                    'controller' => TendanceTypeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        TendancetypePrivileges::TENDANCETYPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => TendanceTypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        TendancetypePrivileges::TENDANCETYPE_AJOUTER,
                    ],
                ],
                [
                    'controller' => TendanceTypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        TendancetypePrivileges::TENDANCETYPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => TendanceTypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        TendancetypePrivileges::TENDANCETYPE_HISTORISER,
                    ],
                ],
                [
                    'controller' => TendanceTypeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        TendancetypePrivileges::TENDANCETYPE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'tendance' => [
                                'label' => 'Tendances',
                                'route' => 'fiche-metier/tendance-type',
                                'resource' => PrivilegeController::getResourceId(TendanceTypeController::class, 'index'),
                                'order' => 1020,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'fiche-metier' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/fiche-metier',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'tendance-type' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/tendance-type',
                            'defaults' => [
                                /** @see TendanceTypeController::indexAction() */
                                'controller' => TendanceTypeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see TendanceTypeController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:tendance-type',
                                    'defaults' => [
                                        /** @see TendanceTypeController::afficherAction() */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:tendance-type',
                                    'defaults' => [
                                        /** @see TendanceTypeController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:tendance-type',
                                    'defaults' => [
                                        /** @see TendanceTypeController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:tendance-type',
                                    'defaults' => [
                                        /** @see TendanceTypeController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:tendance-type',
                                    'defaults' => [
                                        /** @see TendanceTypeController::supprimerAction() */
                                        'action' => 'supprimer',
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
            TendanceTypeService::class => TendanceTypeServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            TendanceTypeController::class => TendanceTypeControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            TendanceTypeForm::class => TendanceTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            TendanceTypeHydrator::class => TendanceTypeHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ]

];