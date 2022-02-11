<?php

namespace Application;

use Application\Controller\ComplementController;
use Application\Controller\ComplementControllerFactory;
use Application\Form\Complement\ComplementForm;
use Application\Form\Complement\ComplementFormFactory;
use Application\Form\Complement\ComplementHydrator;
use Application\Form\Complement\ComplementHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Complement\ComplementService;
use Application\Service\Complement\ComplementServiceFactory;
use Application\View\Helper\ComplementViewHelperFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ComplementController::class,
                    'action' => [
                        'afficher',
                        'ajouter',
                        'modifier',
                        'supprimer',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'complement' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/complement',
                    'defaults' => [
                        //'controller' => ComplementContoller::class,
                        //'action'     => 'afficher',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:complement',
                            'defaults' => [
                                'controller' => ComplementController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter/:attachmenttype/:attachmentid/:type',
                            'defaults' => [
                                'controller' => ComplementController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:complement',
                            'defaults' => [
                                'controller' => ComplementController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:complement',
                            'defaults' => [
                                'controller' => ComplementController::class,
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
            ComplementService::class => ComplementServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ComplementController::class => ComplementControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ComplementForm::class => ComplementFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ComplementHydrator::class => ComplementHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'complement'  => ComplementViewHelperFactory::class,
        ],
    ],
];