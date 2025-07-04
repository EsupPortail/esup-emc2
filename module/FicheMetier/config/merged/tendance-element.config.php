<?php

namespace FicheMetier;

use FicheMetier\Controller\TendanceElementController;
use FicheMetier\Controller\TendanceElementControllerFactory;
use FicheMetier\Form\TendanceElement\TendanceElementForm;
use FicheMetier\Form\TendanceElement\TendanceElementFormFactory;
use FicheMetier\Form\TendanceElement\TendanceElementHydrator;
use FicheMetier\Form\TendanceElement\TendanceElementHydratorFactory;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use FicheMetier\Service\TendanceElement\TendanceElementService;
use FicheMetier\Service\TendanceElement\TendanceElementServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => TendanceElementController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FichemetierPrivileges::FICHEMETIER_INDEX,
                    ],
                ],
                [
                    'controller' => TendanceElementController::class,
                    'action' => [
                        'modifier',
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FichemetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => TendanceElementController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FichemetierPrivileges::FICHEMETIER_DETRUIRE
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
                    'tendance-element' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/tendance-element',
                            'defaults' => [
                                'controller' => TendanceElementController::class,
                                'action' => "index",
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:fiche-metier/:tendance-type',
                                    'defaults' => [
                                        /** @see TendanceElementController::modifierAction() */
                                        'controller' => TendanceElementController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:tendance-element',
                                    'defaults' => [
                                        /** @see TendanceElementController::historiserAction() */
                                        'controller' => TendanceElementController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:tendance-element',
                                    'defaults' => [
                                        /** @see TendanceElementController::restaurerAction() */
                                        'controller' => TendanceElementController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:tendance-element',
                                    'defaults' => [
                                        /** @see TendanceElementController::supprimerAction() */
                                        'controller' => TendanceElementController::class,
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
            TendanceElementService::class => TendanceElementServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            TendanceElementController::class => TendanceElementControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            TendanceElementForm::class => TendanceElementFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            TendanceElementHydrator::class => TendanceElementHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],

];