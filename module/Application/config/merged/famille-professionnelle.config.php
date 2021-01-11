<?php

namespace Application;

use Application\Controller\FamilleProfessionnelleController;
use Application\Controller\FamilleProfessionnelleControllerFactory;
use Application\Provider\Privilege\MetierPrivileges;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_AJOUTER,
                    ],
                ],
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_HISTORISER,
                    ],
                ],
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'effacer',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'famille-professionnelle' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/famille-professionnelle',
                    'defaults' => [
                        'controller' => FamilleProfessionnelleController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:famille-professionnelle',
                            'defaults' => [
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-famille/:famille-professionnelle',
                            'defaults' => [
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-famille/:famille-professionnelle',
                            'defaults' => [
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'effacer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer-famille/:famille-professionnelle',
                            'defaults' => [
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'effacer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FamilleProfessionnelleService::class => FamilleProfessionnelleServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FamilleProfessionnelleController::class => FamilleProfessionnelleControllerFactory::class,
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
