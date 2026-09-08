<?php

namespace Carriere;

use Carriere\Controller\CorrespondanceController;
use Carriere\Controller\CorrespondanceControllerFactory;
use Carriere\Controller\CorrespondanceTypeController;
use Carriere\Controller\CorrespondanceTypeControllerFactory;
use Carriere\Form\Specialite\SpecialiteForm;
use Carriere\Form\Specialite\SpecialiteFormFactory;
use Carriere\Form\Specialite\SpecialiteHydrator;
use Carriere\Form\Specialite\SpecialiteHydratorFactory;
use Carriere\Form\SpecialiteType\SpecialiteTypeForm;
use Carriere\Form\SpecialiteType\SpecialiteTypeFormFactory;
use Carriere\Form\SpecialiteType\SpecialiteTypeHydrator;
use Carriere\Form\SpecialiteType\SpecialiteTypeHydratorFactory;
use Carriere\Provider\Privilege\CorrespondancePrivileges;
use Carriere\Service\Correspondance\CorrespondanceService;
use Carriere\Service\Correspondance\CorrespondanceServiceFactory;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeService;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeServiceFactory;
use Carriere\View\Helper\CorrespondanceViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CorrespondanceController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_INDEX,
                    ],
                ],
                [
                    'controller' => CorrespondanceController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => CorrespondanceController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_AJOUTER,
                    ],
                ],
                [
                    'controller' => CorrespondanceController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_MODIFIER,
                    ],
                ],
                [
                    'controller' => CorrespondanceController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_SUPPRIMER,
                    ],
                ],
                [
                    'controller' => CorrespondanceController::class,
                    'action' => [
                        'afficher-agents',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_LISTER_AGENTS,
                    ],
                ],
                [
                    'controller' => CorrespondanceTypeController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_INDEX,
                    ],
                ],
                [
                    'controller' => CorrespondanceTypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_AJOUTER,
                    ],
                ],
                [
                    'controller' => CorrespondanceTypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_MODIFIER,
                    ],
                ],
                [
                    'controller' => CorrespondanceTypeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_SUPPRIMER,
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
                                'order' => 2005,
                                'label' => 'Spécialités',
                                'route' => 'correspondance',
                                'resource' => PrivilegeController::getResourceId(CorrespondanceController::class, 'index') ,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'carriere' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/carriere',
                    'defaults' => [
                        'controller' => CorrespondanceController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'correspondance-type' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/correspondance-type',
                            'defaults' => [
                                /** @see CorrespondanceTypeController::indexAction() */
                                'controller' => CorrespondanceTypeController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        /** @see CorrespondanceTypeController::ajouterAction() */
                                        'controller' => CorrespondanceTypeController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:correspondance-type',
                                    'defaults' => [
                                        /** @see CorrespondanceTypeController::modifierAction() */
                                        'controller' => CorrespondanceTypeController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:correspondance-type',
                                    'defaults' => [
                                        /** @see CorrespondanceTypeController::supprimerAction() */
                                        'controller' => CorrespondanceTypeController::class,
                                        'action'     => 'supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:type',
                                    'defaults' => [
                                        /** @see CorrespondanceTypeController::afficherAction() */
                                        'controller' => CorrespondanceTypeController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
            'correspondance' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/correspondance',
                    'defaults' => [
                        /** @see CorrespondanceController::indexAction() */
                        'controller' => CorrespondanceController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:correspondance',
                            'defaults' => [
                                /** @see CorrespondanceController::afficherAction() */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'afficher-agents' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agents/:correspondance',
                            'defaults' => [
                                /** @see CorrespondanceController::afficherAgentsAction() */
                                'action'     => 'afficher-agents',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see CorrespondanceController::ajouterAction() */
                                'action'     => 'ajouter',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:correspondance',
                            'defaults' => [
                                /** @see CorrespondanceController::modifierAction() */
                                'action'     => 'modifier',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:correspondance',
                            'defaults' => [
                                /** @see CorrespondanceController::supprimerAction() */
                                'action'     => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            CorrespondanceService::class => CorrespondanceServiceFactory::class,
            CorrespondanceTypeService::class => CorrespondanceTypeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CorrespondanceController::class => CorrespondanceControllerFactory::class,
            CorrespondanceTypeController::class => CorrespondanceTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            SpecialiteForm::class => SpecialiteFormFactory::class,
            SpecialiteTypeForm::class  => SpecialiteTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            SpecialiteHydrator::class => SpecialiteHydratorFactory::class,
            SpecialiteTypeHydrator::class => SpecialiteTypeHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'correspondance' => CorrespondanceViewHelper::class
        ],
    ],
];