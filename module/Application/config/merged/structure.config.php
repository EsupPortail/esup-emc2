<?php

namespace Application;

use Application\Controller\Structure\StructureController;
use Application\Controller\Structure\StructureControllerFactory;
use Application\Form\Structure\StructureFormFactory;
use Application\Form\Structure\StructureForm;
use Application\Form\Structure\StructureHydrator;
use Application\Form\Structure\StructureHydratorFactory;
use Application\Provider\Privilege\StructurePrivileges;
use Application\Service\Structure\StructureService;
use Application\Service\Structure\StructureServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'index',
                        'description',
                        'creer',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'detruire',
                        'ajouter-gestionnaire',
                        'retirer-gestionnaire',
                        'synchroniser',
                    ],
                    'privileges' => [
                        StructurePrivileges::AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'structure' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/structure',
                    'defaults' => [
                        'controller' => StructureController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'creer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],
                    'description' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/description/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'description',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                    'ajouter-gestionnaire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-gestionnaire/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'ajouter-gestionnaire',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'retirer-gestionnaire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-gestionnaire/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'retirer-gestionnaire',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'synchroniser' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/synchroniser',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'synchroniser',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            StructureService::class => StructureServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            StructureController::class => StructureControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            StructureForm::class => StructureFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            StructureHydrator::class => StructureHydratorFactory::class,
        ]
    ]

];