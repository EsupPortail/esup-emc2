<?php

namespace FichePoste;

use FicheMetier\Controller\FicheMetierController;
use FicheMetier\Controller\FicheMetierControllerFactory;
use FicheMetier\Form\Raison\RaisonForm;
use FicheMetier\Form\Raison\RaisonFormFactory;
use FicheMetier\Form\Raison\RaisonHydrator;
use FicheMetier\Form\Raison\RaisonHydratorFactory;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'afficher',
                        'exporter',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_AFFICHER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'ajouter',
                        'dupliquer',
                        'importer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_AJOUTER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_HISTORISER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_DETRUIRE,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'modifier',
                        'modifier-etat',
                        'modifier-metier',
                        'modifier-raison',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fiche-metier' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::afficherAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'exporter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/exporter/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::exporterAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'exporter',
                            ],
                        ],
                    ],
                    'dupliquer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/dupliquer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::dupliquerAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'dupliquer',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::historiserAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::restaurerAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::supprimerAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                    'modifier-etat' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-etat/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierEtatAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'modifier-etat',
                            ],
                        ],
                    ],
                    'modifier-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-metier/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierMetierAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'modifier-metier',
                            ],
                        ],
                    ],
                    'modifier-raison' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-raison/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierRaisonAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'modifier-raison',
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
    'controllers'     => [
        'factories' => [
            FicheMetierController::class => FicheMetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            RaisonForm::class => RaisonFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            RaisonHydrator::class => RaisonHydratorFactory::class,
        ],
    ]

];