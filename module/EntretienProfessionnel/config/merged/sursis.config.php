<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\SursisController;
use EntretienProfessionnel\Controller\SursisControllerFactory;
use EntretienProfessionnel\Form\Sursis\SursisForm;
use EntretienProfessionnel\Form\Sursis\SursisFormFactory;
use EntretienProfessionnel\Form\Sursis\SursisHydrator;
use EntretienProfessionnel\Form\Sursis\SursisHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\SursisPrivileges;
use EntretienProfessionnel\Service\Sursis\SursisService;
use EntretienProfessionnel\Service\Sursis\SursisServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => SursisController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        SursisPrivileges::SURSIS_AFFICHER,
                    ],
                ],
                [
                    'controller' => SursisController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        SursisPrivileges::SURSIS_AJOUTER,
                    ],
                ],
                [
                    'controller' => SursisController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        SursisPrivileges::SURSIS_MODIFIER,
                    ],
                ],
                [
                    'controller' => SursisController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        SursisPrivileges::SURSIS_HISTORISER,
                    ],
                ],
                [
                    'controller' => SursisController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        SursisPrivileges::SURSIS_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'entretien-professionnel' => [
                'child_routes' => [
                    'sursis' => [
                        'type'  => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/sursis',
                        ],
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/ajouter/:entretien-professionnel',
                                    'defaults' => [
                                        'controller' => SursisController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier/:sursis',
                                    'defaults' => [
                                        'controller' => SursisController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser/:sursis',
                                    'defaults' => [
                                        'controller' => SursisController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer/:sursis',
                                    'defaults' => [
                                        'controller' => SursisController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/supprimer/:sursis',
                                    'defaults' => [
                                        'controller' => SursisController::class,
                                        'action'     => 'supprimer',
                                    ],
                                ],
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            SursisService::class => SursisServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            SursisController::class => SursisControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            SursisForm::class => SursisFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            SursisHydrator::class => SursisHydratorFactory::class,
        ],
    ]

];