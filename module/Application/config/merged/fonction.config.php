<?php

namespace Application;

use Application\Controller\Fonction\FonctionController;
use Application\Controller\Fonction\FonctionControllerFactory;
use Application\Form\Fonction\FonctionForm;
use Application\Form\Fonction\FonctionFormFactory;
use Application\Form\Fonction\FonctionHydrator;
use Application\Provider\Privilege\FonctionPrivileges;
use Application\Service\Fonction\FonctionService;
use Application\Service\Fonction\FonctionServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FonctionController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'creer',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'detruire',
                        'synchroniser',
                    ],
                    'privileges' => [
                        FonctionPrivileges::AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fonction' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fonction',
                    'defaults' => [
                        'controller' => FonctionController::class,
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
                                'controller' => FonctionController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:fonction',
                            'defaults' => [
                                'controller' => FonctionController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:fonction',
                            'defaults' => [
                                'controller' => FonctionController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:fonction',
                            'defaults' => [
                                'controller' => FonctionController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:fonction',
                            'defaults' => [
                                'controller' => FonctionController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:fonction',
                            'defaults' => [
                                'controller' => FonctionController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                    'synchroniser' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/synchroniser',
                            'defaults' => [
                                'controller' => FonctionController::class,
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
            FonctionService::class => FonctionServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FonctionController::class => FonctionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FonctionForm::class => FonctionFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            FonctionHydrator::class => FonctionHydrator::class,
        ]
    ]

];