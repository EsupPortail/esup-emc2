<?php

namespace UnicaenValidation;

use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenValidation\Controller\ValidationTypeController;
use UnicaenValidation\Controller\ValidationTypeControllerFactory;
use UnicaenValidation\Form\ValidationType\ValidationTypeForm;
use UnicaenValidation\Form\ValidationType\ValidationTypeFormFactory;
use UnicaenValidation\Form\ValidationType\ValidationTypeHydrator;
use UnicaenValidation\Form\ValidationType\ValidationTypeHydratorFactory;
use UnicaenValidation\Provider\Privilege\ValidationtypePrivileges;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ValidationTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ValidationtypePrivileges::VALIDATIONTYPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => ValidationTypeController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                    ],
                    'privileges' => [
                        ValidationtypePrivileges::VALIDATIONTYPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => ValidationTypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ValidationtypePrivileges::VALIDATIONTYPE_HISTORISER,
                    ],
                ],
                [
                    'controller' => ValidationTypeController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        ValidationtypePrivileges::VALIDATIONTYPE_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'validation' => [
                'child_routes' => [
                    'type' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/type',
                            'defaults' => [
                                'controller' => ValidationTypeController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => ValidationTypeController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:type',
                                    'defaults' => [
                                        'controller' => ValidationTypeController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:type',
                                    'defaults' => [
                                        'controller' => ValidationTypeController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:type',
                                    'defaults' => [
                                        'controller' => ValidationTypeController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:type',
                                    'defaults' => [
                                        'controller' => ValidationTypeController::class,
                                        'action'     => 'detruire',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ValidationTypeService::class => ValidationTypeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ValidationTypeController::class => ValidationTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ValidationTypeForm::class => ValidationTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ValidationTypeHydrator::class => ValidationTypeHydratorFactory::class,
        ],
    ]

];