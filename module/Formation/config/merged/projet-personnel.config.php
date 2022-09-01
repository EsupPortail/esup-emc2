<?php

namespace Formation;

use Formation\Controller\ProjetPersonnelController;
use Formation\Controller\ProjetPersonnelControllerFactory;
use Formation\Provider\Privilege\ProjetpersonnelPrivileges;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ProjetPersonnelController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ProjetpersonnelPrivileges::PROJETPERSONNEL_ACCES
                    ],
                ],
            ],
        ],
    ],


    'router'          => [
        'routes' => [
            'projet-personnel' => [
                'type'  => Segment::class,
                'options' => [
                    'route'    => '/projet-personnel',
                    'defaults' => [
                        'controller' => ProjetPersonnelController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [
            ProjetPersonnelController::class => ProjetPersonnelControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];