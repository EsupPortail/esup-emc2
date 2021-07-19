<?php

namespace Application;

use Application\Controller\NiveauController;
use Application\Controller\NiveauControllerFactory;
use Application\Form\Niveau\NiveauForm;
use Application\Form\Niveau\NiveauFormFactory;
use Application\Form\Niveau\NiveauHydrator;
use Application\Form\Niveau\NiveauHydratorFactory;
use Application\Service\Niveau\NiveauService;
use Application\Service\Niveau\NiveauServiceFactory;
use Metier\Provider\Privilege\MetierPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'index',
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'supprimer',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_AFFICHER,
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
                            'niveau' => [
                                'label'    => 'Niveaux',
                                'route'    => 'niveau',
                                'resource' => PrivilegeController::getResourceId(NiveauController::class, 'index') ,
                                'order'    => 1150,
                                'pages' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'niveau' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/niveau',
                    'defaults' => [
                        'controller' => NiveauController::class,
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
                                'controller' => NiveauController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:niveau',
                            'defaults' => [
                                'controller' => NiveauController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:niveau',
                            'defaults' => [
                                'controller' => NiveauController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:niveau',
                            'defaults' => [
                                'controller' => NiveauController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:niveau',
                            'defaults' => [
                                'controller' => NiveauController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            NiveauService::class => NiveauServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            NiveauController::class => NiveauControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            NiveauForm::class => NiveauFormFactory::class

        ],
    ],
    'hydrators' => [
        'factories' => [
            NiveauHydrator::class => NiveauHydratorFactory::class,
        ],
    ]

];