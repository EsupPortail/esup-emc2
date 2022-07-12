<?php

namespace UnicaenValidation;

use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenValidation\Controller\IndexController;
use UnicaenValidation\Controller\IndexControllerFactory;
use UnicaenValidation\Provider\Privilege\ValidationinstancePrivileges;
use UnicaenValidation\Provider\Privilege\ValidationtypePrivileges;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ValidationtypePrivileges::VALIDATIONTYPE_AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'validation' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/validation',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
//                        'pages' => [
//                            'validations' => [
//                                'label'    => 'Validations',
//                                'route'    => 'validation',
//                                'resource' => ValidationtypePrivileges::getResourceId(ValidationtypePrivileges::VALIDATIONTYPE_AFFICHER),
//                                'order'    => 1111,
//
//                                'pages' => [
//                                    'type' => [
//                                        'label'    => 'Types de validation',
//                                        'route'    => 'validation/type',
//                                        'resource' => ValidationtypePrivileges::getResourceId(ValidationtypePrivileges::VALIDATIONTYPE_AFFICHER),
//                                        'order'    => 1111,
//                                    ],
//                                    'instance' => [
//                                        'label'    => 'Instances de validation',
//                                        'route'    => 'validation/isntance',
//                                        'resource' => ValidationtypePrivileges::getResourceId(ValidationinstancePrivileges::VALIDATIONINSTANCE_AFFICHER),
//                                        'order'    => 1112,
//                                    ],
//                                ],
//                            ],
//                        ],
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
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];