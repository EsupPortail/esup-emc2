<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\DelegueController;
use EntretienProfessionnel\Controller\DelegueControllerFactory;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
use EntretienProfessionnel\Service\Delegue\DelegueService;
use EntretienProfessionnel\Service\Delegue\DelegueServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => DelegueController::class,
                    'action' => [
                        'ajouter',
                        'retirer',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_AJOUTER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'entretien-professionnel' => [
                'child_routes' => [
                    'ajouter-delegue' => [
                        'type'  => Segment::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/ajouter-delegue/:structure/:campagne',
                            'defaults' => [
                                'controller' => DelegueController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'retirer-delegue' => [
                        'type'  => Segment::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/retirer-delegue/:delegue',
                            'defaults' => [
                                'controller' => DelegueController::class,
                                'action'     => 'retirer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            DelegueService::class => DelegueServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            DelegueController::class => DelegueControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];