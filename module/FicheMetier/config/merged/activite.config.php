<?php

namespace FicheMetier;

use FicheMetier\Controller\ActiviteController;
use FicheMetier\Controller\ActiviteControllerFactory;
use FicheMetier\Form\Activite\ActiviteForm;
use FicheMetier\Form\Activite\ActiviteFormFactory;
use FicheMetier\Form\Activite\ActiviteHydrator;
use FicheMetier\Form\Activite\ActiviteHydratorFactory;
use FicheMetier\Provider\Privilege\ActivitePrivileges;
use FicheMetier\Service\Activite\ActiviteService;
use FicheMetier\Service\Activite\ActiviteServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_INDEX,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_AFFICHER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_AJOUTER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_MODIFIER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_HISTORISER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_SUPPRIMER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'rechercher',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_RECHERCHER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'importer',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_IMPORTER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'activite' => [
                                'label' => 'Activités',
                                'route' => 'activite',
                                'resource' => PrivilegeController::getResourceId(ActiviteController::class, 'index'),
                                'order' => 1010,
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
            'activite' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/activite',
                    'defaults' => [
                        /** @see ActiviteController::indexAction() */
                        'controller' => ActiviteController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher/:activite',
                            'defaults' => [
                                /** @see ActiviteController::afficherAction() */
                                'controller' => ActiviteController::class,
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ajouter',
                            'defaults' => [
                                /** @see ActiviteController::ajouterAction() */
                                'controller' => ActiviteController::class,
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier/:activite',
                            'defaults' => [
                                /** @see ActiviteController::modifierAction() */
                                'controller' => ActiviteController::class,
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/historiser/:activite',
                            'defaults' => [
                                /** @see ActiviteController::historiserAction() */
                                'controller' => ActiviteController::class,
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/restaurer/:activite',
                            'defaults' => [
                                /** @see ActiviteController::restaurerAction() */
                                'controller' => ActiviteController::class,
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer/:activite',
                            'defaults' => [
                                /** @see ActiviteController::supprimerAction() */
                                'controller' => ActiviteController::class,
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'rechercher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/rechercher',
                            'defaults' => [
                                /** @see ActiviteController::rechercherAction() */
                                'controller' => ActiviteController::class,
                                'action' => 'rechercher',
                            ],
                        ],
                    ],
                    'importer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/importer',
                            'defaults' => [
                                /** @see ActiviteController::importerAction() */
                                'controller' => ActiviteController::class,
                                'action' => 'importer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ActiviteService::class => ActiviteServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            ActiviteController::class => ActiviteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ActiviteForm::class => ActiviteFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ActiviteHydrator::class => ActiviteHydratorFactory::class,
        ],
    ],

];