<?php

namespace FichePoste;

use FicheMetier\Controller\GraphiqueController;
use FicheMetier\Controller\GraphiqueControllerFactory;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => GraphiqueController::class,
                    'action' => [
                        'graphique-applications',
                        'graphique-competences',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
        'graphique' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/graphique',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'applications' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/applications/:fiche-metier[/:agent]',
                            'defaults' => [
                                /** @see GraphiqueController::graphiqueApplicationsAction() */
                                'controller' => GraphiqueController::class,
                                'action'     => 'graphique-applications',
                            ],
                        ],
                    ],
                    'competences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/competences/:fiche-metier[/:agent]',
                            'defaults' => [
                                /** @see GraphiqueController::graphiqueCompetencesAction() */
                                'controller' => GraphiqueController::class,
                                'action'     => 'graphique-competences',
                            ],
                        ],
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
            GraphiqueController::class => GraphiqueControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];