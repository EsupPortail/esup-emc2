<?php

namespace Application;

use Application\Controller\MesStructuresController;
use Application\Controller\MesStructuresControllerFactory;
use Application\Provider\Privilege\MesStructuresPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MesStructuresController::class,
                    'action' => [
                        'index',
                        'ajouter-fiche-poste',
                        'dupliquer-fiche-poste',
                    ],
                    'privileges' => [
                        MesStructuresPrivileges::GESTION,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'mes-structures' => [
                'type'  => Segment::class,
                'options' => [
                    'route'    => '/mes-structures[/:structure]',
                    'defaults' => [
                        'controller' => MesStructuresController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajouter-fiche-poste' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-fiche-poste[/:agent]',
                            'defaults' => [
                                'controller' => MesStructuresController::class,
                                'action'     => 'ajouter-fiche-poste',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => []
                    ],
                    'dupliquer-fiche-poste' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/dupliquer-fiche-poste/:agent',
                            'defaults' => [
                                'controller' => MesStructuresController::class,
                                'action'     => 'dupliquer-fiche-poste',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => []
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
            MesStructuresController::class => MesStructuresControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];