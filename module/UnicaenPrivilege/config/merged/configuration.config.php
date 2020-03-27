<?php

use UnicaenPrivilege\Controller\ConfigurationController;
use UnicaenPrivilege\Controller\ConfigurationControllerFactory;
use UnicaenPrivilege\Form\CategoriePrivilege\CategoriePrivilegeForm;
use UnicaenPrivilege\Form\CategoriePrivilege\CategoriePrivilegeFormFactory;
use UnicaenPrivilege\Form\CategoriePrivilege\CategoriePrivilegeHydrator;
use UnicaenPrivilege\Form\CategoriePrivilege\CategoriePrivilegeHydratorFactory;
use UnicaenPrivilege\Form\Privilege\PrivilegeForm;
use UnicaenPrivilege\Form\Privilege\PrivilegeFormFactory;
use UnicaenPrivilege\Form\Privilege\PrivilegeHydrator;
use UnicaenPrivilege\Form\Privilege\PrivilegeHydratorFactory;
use UnicaenPrivilege\Provider\Privilege\PrivilegePrivileges;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;


return [

    'bjyauthorize' => [
        'guards' => [
            UnicaenPrivilege\Guard\PrivilegeController::class => [
                [
                    'controller' => ConfigurationController::class,
                    'action' => [
                        'index-configuration-categorie',
                        'gerer-categorie',
                        'provider',
                    ],
                    'privileges' => [
                        PrivilegePrivileges::PRIVILEGE_VOIR,
                    ],
                ],
                [
                    'controller' => ConfigurationController::class,
                    'action' => [
                        'creer-privilege',
                        'creer-categorie',
                    ],
                    'privileges' => [
                        PrivilegePrivileges::PRIVILEGE_AJOUTER,
                    ],
                ],
                [
                    'controller' => ConfigurationController::class,
                    'action' => [
                        'modifier-privilege',
                        'modifier-categorie',
                    ],
                    'privileges' => [
                        PrivilegePrivileges::PRIVILEGE_MODIFIER,
                    ],
                ],
                [
                    'controller' => ConfigurationController::class,
                    'action' => [
                        'detruire-privilege',
                        'detruire-categorie',
                    ],
                    'privileges' => [
                        PrivilegePrivileges::PRIVILEGE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'privilege' => [
                                'pages' => [
                                    'privilege-configuration' => [
                                        'label' => 'Configuration',
                                        'route' => 'configuration-categorie',
                                        'resource' => PrivilegePrivileges::getResourceId(PrivilegePrivileges::PRIVILEGE_AJOUTER),
                                        'order'    => 10001,
                                        'pages' => [
                                            'privilege-configuration' => [
                                                'label' => 'Gérer une catégorie',
                                                'route' => 'configuration-categorie/gerer',
                                                'resource' => PrivilegePrivileges::getResourceId(PrivilegePrivileges::PRIVILEGE_AJOUTER),
                                                'order'    => 10001,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'configuration-categorie' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/configuration-categorie',
                    'defaults' => [
                        'controller' => ConfigurationController::class,
                        'action'     => 'index-configuration-categorie',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'creer' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'creer-categorie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'gerer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/gerer/:categorie',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'gerer-categorie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:categorie',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'modifier-categorie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:categorie',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'detruire-categorie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'provider' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/provider/:categorie',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'provider',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
            'configuration-privilege' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/configuration-privilege',
                    'defaults' => [
                        'controller' => ConfigurationController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'creer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/creer/:categorie',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'creer-privilege',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:privilege',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'modifier-privilege',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:privilege',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'detruire-privilege',
                            ],
                        ],
                        'may_terminate' => true,
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
            ConfigurationController::class => ConfigurationControllerFactory::class,

        ],
    ],
    'form_elements' => [
        'factories' => [
            CategoriePrivilegeForm::class => CategoriePrivilegeFormFactory::class,
            PrivilegeForm::class => PrivilegeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CategoriePrivilegeHydrator::class => CategoriePrivilegeHydratorFactory::class,
            PrivilegeHydrator::class => PrivilegeHydratorFactory::class,
        ],
    ]
];