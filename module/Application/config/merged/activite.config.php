<?php

namespace Application;

use Application\Controller\ActiviteController;
use Application\Controller\ActiviteControllerFactory;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormFactory;
use Application\Form\Activite\ActiviteHydrator;
use Application\Form\Activite\ActiviteHydratorFactory;
use Application\Provider\Privilege\ActivitePrivileges;
use Application\Service\Activite\ActiviteService;
use Application\Service\Activite\ActiviteServiceFactory;
use Application\Service\ActiviteDescription\ActiviteDescriptionService;
use Application\Service\ActiviteDescription\ActiviteDescriptionServiceFactory;
use Application\View\Helper\ActiviteViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                        'creer',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_AJOUTER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'editer',
                        'modifier',

                        'modifier-libelle',
                        'modifier-application',
                        'modifier-competence',
                        'modifier-formation',

                        'ajouter-description',
                        'ajouter-descriptions',
                        'modifier-description',
                        'supprimer-description',
                        'update-ordre-description',

                        'ajouter-niveaux',
                        'modifier-niveaux',
                        'retirer-niveaux',
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
                        'detruire',
                    ],
                    'privileges' => [
                        ActivitePrivileges::ACTIVITE_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'mission-pincipale' => [
                                'label'    => 'Missions principales',
                                'route'    => 'activite',
                                'resource' => ActivitePrivileges::getResourceId(ActivitePrivileges::ACTIVITE_AFFICHER),
                                'order'    => 1000,
                                'pages' => [
                                    'modifier' => [
                                        'label'    => 'Modifier une activité',
                                        'route'    => 'activite/modifier',
                                        'resource' => ActivitePrivileges::getResourceId(ActivitePrivileges::ACTIVITE_MODIFIER),
                                        'order'    => 1000,
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'activite' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/mission-principale',
                    'defaults' => [
                        'controller' => ActiviteController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier[/:activite]',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'update-ordre-description' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/update-ordre-description/:activite/:ordre',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'update-ordre-description',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                    'modifier-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-application/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier-application',
                            ],
                        ],
                    ],
                    'modifier-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-competence/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier-competence',
                            ],
                        ],
                    ],
                    'modifier-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-formation/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier-formation',
                            ],
                        ],
                    ],
                    'modifier-libelle' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-libelle/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier-libelle',
                            ],
                        ],
                    ],
                    'creer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],
                    'ajouter-description' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-description/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'ajouter-description',
                            ],
                        ],
                    ],
                    'ajouter-descriptions' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-descriptions/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'ajouter-descriptions',
                            ],
                        ],
                    ],
                    'modifier-description' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-description/:description',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier-description',
                            ],
                        ],
                    ],
                    'supprimer-description' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-description/:description',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'supprimer-description',
                            ],
                        ],
                    ],
                    /** GESTION DES ENVELOPPES DE NIVEAUX *****************/
                    'ajouter-niveaux' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-niveaux/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'ajouter-niveaux',
                            ],
                        ],
                    ],
                    'modifier-niveaux' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-niveaux/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier-niveaux',
                            ],
                        ],
                    ],
                    'retirer-niveaux' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-niveaux/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'retirer-niveaux',
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
            ActiviteDescriptionService::class => ActiviteDescriptionServiceFactory::class,
        ],
    ],
    'controllers'     => [
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
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'activite'  => ActiviteViewHelper::class,
        ],
    ],

];