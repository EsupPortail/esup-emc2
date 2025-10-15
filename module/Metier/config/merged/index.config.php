<?php

namespace Metier;

use Laminas\Router\Http\Literal;
use Metier\Controller\IndexController;
use Metier\Controller\IndexControllerFactory;
use Metier\Provider\Privilege\DomainePrivileges;
use Metier\Provider\Privilege\FamilleprofessionnellePrivileges;
use Metier\Provider\Privilege\MetierPrivileges;
use Metier\Provider\Privilege\ReferentielmetierPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index'
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_INDEX,
                        FamilleprofessionnellePrivileges::FAMILLE_PROFESSIONNELLE_INDEX,
                        ReferentielmetierPrivileges::REFERENTIEL_INDEX,
                        MetierPrivileges::METIER_CARTOGRAPHIE,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'metier' => [
                'child_routes' => [
                    'referentiel' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route' => '/index',
                            'defaults' => [
                                /** @see IndexController::indexAction() */
                                'controller' => IndexController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate'=> true,
                        'child_routes' => [
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            [
                                'order' => 1000,
                                'label' => 'Ressources liées aux métiers',
                                'route' => 'metier',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
                                'dropdown-header' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
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