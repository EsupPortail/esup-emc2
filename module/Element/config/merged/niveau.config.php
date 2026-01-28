<?php

namespace Element;

use Element\Controller\NiveauController;
use Element\Controller\NiveauControllerFactory;
use Element\Form\Niveau\NiveauForm;
use Element\Form\Niveau\NiveauFormFactory;
use Element\Form\Niveau\NiveauHydrator;
use Element\Form\Niveau\NiveauHydratorFactory;
use Element\Form\SelectionNiveau\SelectionNiveauForm;
use Element\Form\SelectionNiveau\SelectionNiveauFormFactory;
use Element\Form\SelectionNiveau\SelectionNiveauHydrator;
use Element\Form\SelectionNiveau\SelectionNiveauHydratorFactory;
use Element\Provider\Privilege\NiveauPrivileges;
use Element\Service\NiveauMaitrise\NiveauMaitriseService;
use Element\Service\NiveauMaitrise\NiveauMaitriseServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_INDEX,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_AFFICHER,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_AJOUTER,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_MODIFIER,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_HISTORISER,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_EFFACER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'element' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/element',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'niveau' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/niveau',
                            'defaults' => [
                                /** @see NiveauController::indexAction() */
                                'controller' => NiveauController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        /** @see NiveauController::ajouterAction() */
                                        'controller' => NiveauController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:maitrise',
                                    'defaults' => [
                                        /** @see NiveauController::afficherAction() */
                                        'controller' => NiveauController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:maitrise',
                                    'defaults' => [
                                        /** @see NiveauController::modifierAction() */
                                        'controller' => NiveauController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:maitrise',
                                    'defaults' => [
                                        /** @see NiveauController::historiserAction() */
                                        'controller' => NiveauController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:maitrise',
                                    'defaults' => [
                                        /** @see NiveauController::restaurerAction() */
                                        'controller' => NiveauController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:maitrise',
                                    'defaults' => [
                                        /** @see NiveauController::supprimerAction() */
                                        'controller' => NiveauController::class,
                                        'action'     => 'supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
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
                            [
                                'label' => 'Niveaux de maîtrise',
                                'route' => 'element/niveau',
                                'resource' => PrivilegeController::getResourceId(NiveauController::class, 'index') ,
                                'order' => 3300,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            NiveauMaitriseService::class => NiveauMaitriseServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            NiveauController::class => NiveauControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            NiveauForm::class => NiveauFormFactory::class,
            SelectionNiveauForm::class => SelectionNiveauFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            NiveauHydrator::class => NiveauHydratorFactory::class,
            SelectionNiveauHydrator::class => SelectionNiveauHydratorFactory::class,
        ],
    ]

];