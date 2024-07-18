<?php

namespace FichePoste;

use FicheMetier\Controller\RepertoireController;
use FicheMetier\Controller\RepertoireControllerFactory;
use FicheMetier\Service\Repertoire\RepertoireService;
use FicheMetier\Service\Repertoire\RepertoireServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => RepertoireController::class,
                    'action' => [
                        'lire',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'fiche-metier' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/fiche-metier',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'repertoire' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/repertoire',
                            'defaults' => [
                                /** @see RepertoireController::lireAction() */
                                'controller' => RepertoireController::class,
                                'action' => 'lire',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            RepertoireService::class => RepertoireServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            RepertoireController::class => RepertoireControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ]

];