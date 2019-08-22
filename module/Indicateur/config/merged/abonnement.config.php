<?php

namespace Application;

use Indicateur\Controller\Abonnement\AbonnementController;
use Indicateur\Controller\Abonnement\AbonnementControllerFactory;
use Indicateur\Service\Abonnement\AbonnementService;
use Indicateur\Service\Abonnement\AbonnementServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [

            ],
        ],
    ],

    'router'          => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
            AbonnementService::class => AbonnementServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AbonnementController::class => AbonnementControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];