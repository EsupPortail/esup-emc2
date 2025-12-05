<?php

namespace Carriere;

use Carriere\Controller\NiveauFonctionController;
use Carriere\Controller\NiveauFonctionControllerFactory;
use Carriere\Form\NiveauFonction\NiveauFonctionForm;
use Carriere\Form\NiveauFonction\NiveauFonctionFormFactory;
use Carriere\Form\NiveauFonction\NiveauFonctionHydrator;
use Carriere\Form\NiveauFonction\NiveauFonctionHydratorFactory;
use Carriere\Provider\Privilege\NiveaufonctionPrivileges;
use Carriere\Service\NiveauFonction\NiveauFonctionService;
use Carriere\Service\NiveauFonction\NiveauFonctionServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => NiveauFonctionController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        NiveaufonctionPrivileges::NIVEAUFONCTION_INDEX,
                    ],
                ],
                [
                    'controller' => NiveauFonctionController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        NiveaufonctionPrivileges::NIVEAUFONCTION_AFFICHER,
                    ],
                ],
                [
                    'controller' => NiveauFonctionController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        NiveaufonctionPrivileges::NIVEAUFONCTION_AJOUTER,
                    ],
                ],
                [
                    'controller' => NiveauFonctionController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        NiveaufonctionPrivileges::NIVEAUFONCTION_MODIFIER,
                    ],
                ],
                [
                    'controller' => NiveauFonctionController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        NiveaufonctionPrivileges::NIVEAUFONCTION_HISTORISER,
                    ],
                ],
                [
                    'controller' => NiveauFonctionController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        NiveaufonctionPrivileges::NIVEAUFONCTION_SUPPRIMER,
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
                            'niveau-fonction' => [
                                'label' => 'Niveau de fonction',
                                'route' => 'niveau-fonction',
                                'resource' => PrivilegeController::getResourceId(NiveauFonctionController::class, 'index'),
                                'order' => 2501,
                                'pages' => [],
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
            'niveau-fonction' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/niveau-fonction',
                    'defaults' => [
                        /** @see NiveauFonctionController::indexAction() */
                        'controller' => NiveauFonctionController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher/:niveau-fonction',
                            'defaults' => [
                                /** @see NiveauFonctionController::afficherAction() */
                                'controller' => NiveauFonctionController::class,
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ajouter',
                            'defaults' => [
                                /** @see NiveauFonctionController::ajouterAction() */
                                'controller' => NiveauFonctionController::class,
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier/:niveau-fonction',
                            'defaults' => [
                                /** @see NiveauFonctionController::modifierAction() */
                                'controller' => NiveauFonctionController::class,
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/historiser/:niveau-fonction',
                            'defaults' => [
                                /** @see NiveauFonctionController::historiserAction() */
                                'controller' => NiveauFonctionController::class,
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/restaurer/:niveau-fonction',
                            'defaults' => [
                                /** @see NiveauFonctionController::restaurerAction() */
                                'controller' => NiveauFonctionController::class,
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer/:niveau-fonction',
                            'defaults' => [
                                /** @see NiveauFonctionController::supprimerAction() */
                                'controller' => NiveauFonctionController::class,
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            NiveauFonctionService::class => NiveauFonctionServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            NiveauFonctionController::class => NiveauFonctionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            NiveauFonctionForm::class => NiveauFonctionFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            NiveauFonctionHydrator::class => NiveauFonctionHydratorFactory::class
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
];