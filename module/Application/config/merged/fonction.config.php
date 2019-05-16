<?php

namespace Application;

use Application\Controller\Fonction\FonctionController;
use Application\Controller\Fonction\FonctionControllerFactory;
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
        ],
    ],
    'hydrators' => [
        'invokables' => [
        ]
    ]

];