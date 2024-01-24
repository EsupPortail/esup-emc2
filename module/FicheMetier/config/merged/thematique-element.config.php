<?php

namespace FicheMetier;

use FicheMetier\Controller\ThematiqueElementController;
use FicheMetier\Controller\ThematiqueElementControllerFactory;
use FicheMetier\Form\ThematiqueElement\ThematiqueElementForm;
use FicheMetier\Form\ThematiqueElement\ThematiqueElementFormFactory;
use FicheMetier\Form\ThematiqueElement\ThematiqueElementHydrator;
use FicheMetier\Form\ThematiqueElement\ThematiqueElementHydratorFactory;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use FicheMetier\Service\ThematiqueElement\ThematiqueElementService;
use FicheMetier\Service\ThematiqueElement\ThematiqueElementServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ThematiqueElementController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FichemetierPrivileges::FICHEMETIER_INDEX,
                    ],
                ],
                [
                    'controller' => ThematiqueElementController::class,
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
                    'controller' => ThematiqueElementController::class,
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
                    'thematique-element' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/thematique-element',
                            'defaults' => [
                                'controller' => ThematiqueElementController::class,
                                'action' => "index",
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:fiche-metier/:thematique-type',
                                    'defaults' => [
                                        /** @see ThematiqueElementController::modifierAction() */
                                        'controller' => ThematiqueElementController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:thematique-element',
                                    'defaults' => [
                                        /** @see ThematiqueElementController::historiserAction() */
                                        'controller' => ThematiqueElementController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:thematique-element',
                                    'defaults' => [
                                        /** @see ThematiqueElementController::restaurerAction() */
                                        'controller' => ThematiqueElementController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:thematique-element',
                                    'defaults' => [
                                        /** @see ThematiqueElementController::supprimerAction() */
                                        'controller' => ThematiqueElementController::class,
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
            ThematiqueElementService::class => ThematiqueElementServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            ThematiqueElementController::class => ThematiqueElementControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            ThematiqueElementForm::class => ThematiqueElementFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ThematiqueElementHydrator::class => ThematiqueElementHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],

];