<?php

namespace Formation;

use Formation\Controller\StagiaireExterneController;
use Formation\Controller\StagiaireExterneControllerFactory;
use Formation\Form\StagiaireExterne\StagiaireExterneForm;
use Formation\Form\StagiaireExterne\StagiaireExterneFormFactory;
use Formation\Form\StagiaireExterne\StagiaireExterneHydrator;
use Formation\Form\StagiaireExterne\StagiaireExterneHydratorFactory;
use Formation\Provider\Privilege\StagiaireexternePrivileges;
use Formation\Service\StagiaireExterne\StagiaireExterneService;
use Formation\Service\StagiaireExterne\StagiaireExterneServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => StagiaireExterneController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        StagiaireexternePrivileges::STAGIAIREEXTERNE_INDEX,
                    ],
                ],
                [
                    'controller' => StagiaireExterneController::class,
                    'action' => [
                        'afficher',
                        'historique',
                    ],
                    'privileges' => [
                        StagiaireexternePrivileges::STAGIAIREEXTERNE_AFFICHER,
                    ],
                ],
                [
                    'controller' => StagiaireExterneController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        StagiaireexternePrivileges::STAGIAIREEXTERNE_AJOUTER,
                    ],
                ],
                [
                    'controller' => StagiaireExterneController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        StagiaireexternePrivileges::STAGIAIREEXTERNE_MODIFIER,
                    ],
                ],
                [
                    'controller' => StagiaireExterneController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        StagiaireexternePrivileges::STAGIAIREEXTERNE_HISTORISER,
                    ],
                ],
                [
                    'controller' => StagiaireExterneController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        StagiaireexternePrivileges::STAGIAIREEXTERNE_SUPPRIMER,
                    ],
                ],
                [
                    'controller' => StagiaireExterneController::class,
                    'action' => [
                        'rechercher',
                    ],
                    'privileges' => [
                        StagiaireexternePrivileges::STAGIAIREEXTERNE_RECHERCHER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'pages' => [
                            'stagiaire-externe' => [
                                'label'    => 'Stagiaires externe',
                                'route'    => 'stagiaire-externe',
                                'resource' => PrivilegeController::getResourceId(StagiaireExterneController::class, 'index') ,
                                'order'    => 209,
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
            'stagiaire-externe' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/stagiaire-externe',
                    'defaults' => [
                        /** @see StagiaireExterneController::indexAction() */
                        'controller' => StagiaireExterneController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:stagiaire-externe',
                            'defaults' => [
                                /** @see StagiaireExterneController::afficherAction() */
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'historique' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historique/:stagiaire-externe',
                            'defaults' => [
                                /** @see StagiaireExterneController::historiqueAction() */
                                'action' => 'historique',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see StagiaireExterneController::ajouterAction() */
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:stagiaire-externe',
                            'defaults' => [
                                /** @see StagiaireExterneController::modifierAction() */
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:stagiaire-externe',
                            'defaults' => [
                                /** @see StagiaireExterneController::historiserAction() */
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:stagiaire-externe',
                            'defaults' => [
                                /** @see StagiaireExterneController::restaurerAction() */
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:stagiaire-externe',
                            'defaults' => [
                                /** @see StagiaireExterneController::supprimerAction() */
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'rechercher' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher',
                            'defaults' => [
                                /** @see StagiaireExterneController::rechercherAction() */
                                'action' => 'rechercher',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            StagiaireExterneService::class => StagiaireExterneServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            StagiaireExterneController::class => StagiaireExterneControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            StagiaireExterneForm::class => StagiaireExterneFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            StagiaireExterneHydrator::class => StagiaireExterneHydratorFactory::class,
        ],
    ],

];