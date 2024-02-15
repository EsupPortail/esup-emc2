<?php

namespace Formation;

use Formation\Controller\FormateurController;
use Formation\Controller\FormateurControllerFactory;
use Formation\Form\Formateur\FormateurForm;
use Formation\Form\Formateur\FormateurFormFactory;
use Formation\Form\Formateur\FormateurHydrator;
use Formation\Form\Formateur\FormateurHydratorFactory;
use Formation\Form\SelectionFormateur\SelectionFormateurForm;
use Formation\Form\SelectionFormateur\SelectionFormateurFormFactory;
use Formation\Form\SelectionFormateur\SelectionFormateurHydrator;
use Formation\Form\SelectionFormateur\SelectionFormateurHydratorFactory;
use Formation\Provider\Privilege\FormateurPrivileges;
use Formation\Service\Formateur\FormateurService;
use Formation\Service\Formateur\FormateurServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormateurController::class,
                    'action' => [
                        'index',
                        'rechercher',
                        'rechercher-rattachement',
                    ],
                    'privileges' => [
                        FormateurPrivileges::FORMATEUR_INDEX,
                    ],
                ],
                [
                    'controller' => FormateurController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FormateurPrivileges::FORMATEUR_AFFICHER,
                    ],
                ],
                [
                    'controller' => FormateurController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FormateurPrivileges::FORMATEUR_AJOUTER,
                    ],
                ],
                [
                    'controller' => FormateurController::class,
                    'action' => [
                        'modifier',
                        'creer-compte',
                        'associer-compte',
                        'deassocier-compte'
                    ],
                    'privileges' => [
                        FormateurPrivileges::FORMATEUR_MODIFIER,
                    ],
                ],
                [
                    'controller' => FormateurController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FormateurPrivileges::FORMATEUR_HISTORISER,
                    ],
                ],
                [
                    'controller' => FormateurController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FormateurPrivileges::FORMATEUR_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'formation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/formation',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'formateur' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/formateur',
                            'defaults' => [
                                /** @see FormateurController::indexAction() */
                                'controller' => FormateurController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:formateur',
                                    'defaults' => [
                                        /** @see FormateurController::afficherAction() */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'rechercher' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/rechercher',
                                    'defaults' => [
                                        /** @see FormateurController::rechercherAction() */
                                        'action' => 'rechercher',
                                    ],
                                ],
                            ],
                            'rechercher-rattachement' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/rechercher-rattachement',
                                    'defaults' => [
                                        /** @see FormateurController::rechercherRattachementAction() */
                                        'action' => 'rechercher-rattachement',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see FormateurController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:formateur',
                                    'defaults' => [
                                        /** @see FormateurController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:formateur',
                                    'defaults' => [
                                        /** @see FormateurController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:formateur',
                                    'defaults' => [
                                        /** @see FormateurController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:formateur',
                                    'defaults' => [
                                        /** @see FormateurController::supprimerAction() */
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                            'creer-compte' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/creer-compte/:formateur',
                                    'defaults' => [
                                        /** @see FormateurController::creerCompteAction() */
                                        'action' => 'creer-compte',
                                    ],
                                ],
                            ],
                            'associer-compte' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/associer-compte/:formateur',
                                    'defaults' => [
                                        /** @see FormateurController::associerCompteAction() */
                                        'action' => 'associer-compte',
                                    ],
                                ],
                            ],
                            'deassocier-compte' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/deassocier-compte/:formateur',
                                    'defaults' => [
                                        /** @see FormateurController::deassocierCompteAction() */
                                        'action' => 'deassocier-compte',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'pages' => [
                            'formateur' => [
                                'label' => 'Formateurs',
                                'route' => 'formation/formateur',
                                'resource' => PrivilegeController::getResourceId(FormateurController::class, 'index'),
                                'order' => 225,
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
            FormateurService::class => FormateurServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            FormateurController::class => FormateurControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormateurForm::class => FormateurFormFactory::class,
            SelectionFormateurForm::class => SelectionFormateurFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormateurHydrator::class => FormateurHydratorFactory::class,
            //attention bidon !!!
            SelectionFormateurHydrator::class => SelectionFormateurHydratorFactory::class,
        ],
    ]

];