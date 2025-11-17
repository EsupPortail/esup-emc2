<?php

namespace FichePoste;

use FicheMetier\Controller\ThematiqueTypeController;
use FicheMetier\Controller\ThematiqueTypeControllerFactory;
use FicheMetier\Form\ThematiqueType\ThematiqueTypeForm;
use FicheMetier\Form\ThematiqueType\ThematiqueTypeFormFactory;
use FicheMetier\Form\ThematiqueType\ThematiqueTypeHydrator;
use FicheMetier\Form\ThematiqueType\ThematiqueTypeHydratorFactory;
use FicheMetier\Provider\Privilege\ThematiquetypePrivileges;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeService;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ThematiqueTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ThematiquetypePrivileges::THEMATIQUETYPE_INDEX,
                    ],
                ],
                [
                    'controller' => ThematiqueTypeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ThematiquetypePrivileges::THEMATIQUETYPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => ThematiqueTypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ThematiquetypePrivileges::THEMATIQUETYPE_AJOUTER,
                    ],
                ],
                [
                    'controller' => ThematiqueTypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ThematiquetypePrivileges::THEMATIQUETYPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => ThematiqueTypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ThematiquetypePrivileges::THEMATIQUETYPE_HISTORISER,
                    ],
                ],
                [
                    'controller' => ThematiqueTypeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ThematiquetypePrivileges::THEMATIQUETYPE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

//    'navigation' => [
//        'default' => [
//            'home' => [
//                'pages' => [
//                    'ressource' => [
//                        'pages' => [
//                            'thematique' => [
//                                'label' => 'Thématiques',
//                                'route' => 'fiche-metier/thematique-type',
//                                'resource' => PrivilegeController::getResourceId(ThematiqueTypeController::class, 'index'),
//                                'order' => 1020,
//                                'icon' => 'fas fa-angle-right',
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ],
//    ],

    'router' => [
        'routes' => [
            'fiche-metier' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/fiche-metier',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'thematique-type' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/thematique-type',
                            'defaults' => [
                                /** @see ThematiqueTypeController::indexAction() */
                                'controller' => ThematiqueTypeController::class,
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
                                        /** @see ThematiqueTypeController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:thematique-type',
                                    'defaults' => [
                                        /** @see ThematiqueTypeController::afficherAction() */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:thematique-type',
                                    'defaults' => [
                                        /** @see ThematiqueTypeController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:thematique-type',
                                    'defaults' => [
                                        /** @see ThematiqueTypeController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:thematique-type',
                                    'defaults' => [
                                        /** @see ThematiqueTypeController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:thematique-type',
                                    'defaults' => [
                                        /** @see ThematiqueTypeController::supprimerAction() */
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
            ThematiqueTypeService::class => ThematiqueTypeServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            ThematiqueTypeController::class => ThematiqueTypeControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            ThematiqueTypeForm::class => ThematiqueTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ThematiqueTypeHydrator::class => ThematiqueTypeHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ]

];