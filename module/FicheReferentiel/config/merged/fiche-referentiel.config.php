<?php

namespace FicheReferentiel;

use FicheReferentiel\Controller\FicheReferentielController;
use FicheReferentiel\Controller\FicheReferentielControllerFactory;
use FicheReferentiel\Provider\Privilege\FichereferentielPrivileges;
use FicheReferentiel\Service\FicheReferentiel\FicheReferentielService;
use FicheReferentiel\Service\FicheReferentiel\FicheReferentielServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheReferentielController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FichereferentielPrivileges::FICHEREFERENTIEL_INDEX
                    ],
                ],
                [
                    'controller' => FicheReferentielController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FichereferentielPrivileges::FICHEREFERENTIEL_AFFICHER
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'fiche-ref' => [
                                'label' => 'Fiches Référentiels',
                                'route' => 'fiche-referentiel',
                                'resource' => PrivilegeController::getResourceId(FicheReferentielController::class, 'index'),
                                'order'    => 2041,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'fiche-referentiel' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-referentiel',
                    'defaults' => [
                        /** @see FicheReferentielController::indexAction() */
                        'controller' => FicheReferentielController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:fiche-referentiel',
                            'defaults' => [
                                /** @see FicheReferentielController::afficherAction() */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FicheReferentielService::class => FicheReferentielServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            FicheReferentielController::class => FicheReferentielControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];