<?php

namespace Carriere;

use Carriere\Controller\FonctionTypeController;
use Carriere\Controller\FonctionTypeControllerFactory;
use Carriere\Form\FonctionType\FonctionTypeForm;
use Carriere\Form\FonctionType\FonctionTypeFormFactory;
use Carriere\Form\FonctionType\FonctionTypeHydrator;
use Carriere\Form\FonctionType\FonctionTypeHydratorFactory;
use Carriere\Provider\Privilege\FonctiontypePrivileges;
use Carriere\Service\FonctionType\FonctionTypeService;
use Carriere\Service\FonctionType\FonctionTypeServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FonctionTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FonctiontypePrivileges::FONCTIONTYPE_INDEX,
                    ],
                ],
                [
                    'controller' => FonctionTypeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FonctiontypePrivileges::FONCTIONTYPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => FonctionTypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FonctiontypePrivileges::FONCTIONTYPE_AJOUTER,
                    ],
                ],
                [
                    'controller' => FonctionTypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        FonctiontypePrivileges::FONCTIONTYPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => FonctionTypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FonctiontypePrivileges::FONCTIONTYPE_HISTORISER,
                    ],
                ],
                [
                    'controller' => FonctionTypeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FonctiontypePrivileges::FONCTIONTYPE_SUPPRIMER,
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
                            'fonction-type' => [
                                'label' => 'Type de fonction',
                                'route' => 'fonction-type',
                                'resource' => PrivilegeController::getResourceId(FonctionTypeController::class, 'index'),
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
            'fonction-type' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/fonction-type',
                    'defaults' => [
                        /** @see FonctionTypeController::indexAction() */
                        'controller' => FonctionTypeController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher/:fonction-type',
                            'defaults' => [
                                /** @see FonctionTypeController::afficherAction() */
                                'controller' => FonctionTypeController::class,
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ajouter',
                            'defaults' => [
                                /** @see FonctionTypeController::ajouterAction() */
                                'controller' => FonctionTypeController::class,
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier/:fonction-type',
                            'defaults' => [
                                /** @see FonctionTypeController::modifierAction() */
                                'controller' => FonctionTypeController::class,
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/historiser/:fonction-type',
                            'defaults' => [
                                /** @see FonctionTypeController::historiserAction() */
                                'controller' => FonctionTypeController::class,
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/restaurer/:fonction-type',
                            'defaults' => [
                                /** @see FonctionTypeController::restaurerAction() */
                                'controller' => FonctionTypeController::class,
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer/:fonction-type',
                            'defaults' => [
                                /** @see FonctionTypeController::supprimerAction() */
                                'controller' => FonctionTypeController::class,
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
            FonctionTypeService::class => FonctionTypeServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            FonctionTypeController::class => FonctionTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FonctionTypeForm::class => FonctionTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FonctionTypeHydrator::class => FonctionTypeHydratorFactory::class
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
];