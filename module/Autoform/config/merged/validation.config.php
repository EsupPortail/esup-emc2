<?php

namespace Autoform;

use Application\Provider\Privilege\AdministrationPrivileges;
use Autoform\Controller\ValidationController;
use Autoform\Controller\ValidationControllerFactory;
use Autoform\Provider\Privilege\FormulairePrivileges;
use Autoform\Service\Validation\ValidationReponseService;
use Autoform\Service\Validation\ValidationReponseServiceFactory;
use Autoform\Service\Validation\ValidationService;
use Autoform\Service\Validation\ValidationServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ValidationController::class,
                    'action' => [
                        'index',
                        'creer',
                        'afficher-validation',
                        'afficher-resultat',
                        'export-pdf',
                        'historiser',
                        'restaurer',
                        'detruire',
                    ],
                    'privileges' => [
                        FormulairePrivileges::AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
//                    'gestion' => [
//                        'pages' => [
//                            'validation' => [
//                                'label'    => 'Validation',
//                                'route'    => 'autoform/validations',
//                                'resource' => AdministrationPrivileges::getResourceId(AdministrationPrivileges::ADMINISTRATION_VOIR),
//                                'order'    => 3,
//                            ],
//                        ],
//                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'autoform' => [
                'child_routes' => [
                    'validations' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/validations',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'creer-validation' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/creer-validation/:instance/:type',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],
                    'validation' => [
                        'type' => Segment::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/validation',
                        ],
                        'child_routes' => [
                            'afficher-validation' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/afficher-validation/:instance/:type',
                                    'defaults' => [
                                        'controller' => ValidationController::class,
                                        'action'     => 'afficher-validation',
                                    ],
                                ],
                            ],
                            'afficher-resultat' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/afficher-resultat/:validation',
                                    'defaults' => [
                                        'controller' => ValidationController::class,
                                        'action'     => 'afficher-resultat',
                                    ],
                                ],
                            ],
                            'export-pdf' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/export-pdf/:validation',
                                    'defaults' => [
                                        'controller' => ValidationController::class,
                                        'action'     => 'export-pdf',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser/:validation',
                                    'defaults' => [
                                        'controller' => ValidationController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer:validation',
                                    'defaults' => [
                                        'controller' => ValidationController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/detruire:validation',
                                    'defaults' => [
                                        'controller' => ValidationController::class,
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
            ValidationService::class => ValidationServiceFactory::class,
            ValidationReponseService::class => ValidationReponseServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ValidationController::class => ValidationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ],

    'view_helpers' => [
        'invokables' => [
        ],
    ],

];