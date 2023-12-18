<?php

namespace Formation;

use Formation\Controller\FormationInstanceInscritController;
use Formation\Controller\FormationInstanceInscritControllerFactory;
use Formation\Controller\PlanDeFormationController;
use Formation\Controller\ProjetPersonnelController;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_GERER_INSCRIPTION,
                    ],
                ],
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                        'formations',
                        'inscriptions',
                        'inscription-interne',
                        'inscription-externe',
                    ],
                    'roles' => [
                        'Agent',
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'formation' => [
            'home' => [
                'pages' => [
                    [
                        'order' => 100,
                        'label' => "Plan de formation",
                        'route' => 'plan-de-formation/courant',
                        'resource' => PrivilegeController::getResourceId(PlanDeFormationController::class, 'courant'),
                    ],
                    [
                        'order' => 200,
                        'label' => "M'inscrire",
                        'route' => 'home',
                        'resources' => [
                            PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscription-interne'),
                            PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscription-externe'),
                        ],
                        'dropdown-header' => true,
                        'pages' => [
                            [
                                'order' => 310,
                                'label' => 'Formation du plan de formation',
                                'route' => 'inscription-interne',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscription-interne'),
                            ],
                            [
                                'order' => 320,
                                'label' => 'Stage hors plan de formation',
                                'route' => 'inscription-externe',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscription-externe'),
                            ],
                        ],
                    ],
                    [
                        'order' => 300,
                        'label' => 'Mes formations',
                        'title' => 'Mes formations choucroute',
                        'route' => 'home',
                        'resources' => [
                            PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscriptions'),
                            PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'formations'),
                        ],
                        'dropdown-header' => true,
                        'pages' => [
                            [
                                'order' => 310,
                                'label' => 'Mes inscriptions en cours',
                                'route' => 'inscriptions',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscriptions'),
                            ],
                            [
                                'order' => 320,
                                'label' => 'Mes formations réalisées',
                                'route' => 'formations',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'formations'),
                            ],
                        ],
                    ],
                    [
                        'order' => 400,
                        'label' => 'Mon projet personnel',
                        'route' => 'projet-personnel',
                        'resource' => PrivilegeController::getResourceId(ProjetPersonnelController::class, 'index'),
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'formations' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/mes-formations[/:agent]',
                    'defaults' => [
                        /** @see FormationInstanceInscritController::formationsAction() */
                        'controller' => FormationInstanceInscritController::class,
                        'action' => 'formations',
                    ],
                ],
            ],
            'inscriptions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/mes-inscriptions[/:agent]',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action' => 'inscriptions',
                    ],
                ],
            ],
            'inscription-interne' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/inscription-interne[/:agent]',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action' => 'inscription-interne',
                    ],
                ],
            ],
            'inscription-externe' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/inscription-externe[/:agent]',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action' => 'inscription-externe',
                    ],
                ],
            ],
//            'inscription-formation' => [
//                'type' => Literal::class,
//                'options' => [
//                    'route' => '/inscription-formation',
//                    'defaults' => [
//                        'controller' => FormationInstanceInscritController::class,
//                        'action' => 'inscription-formation',
//                    ],
//                ],
//            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers' => [
        'factories' => [
            FormationInstanceInscritController::class => FormationInstanceInscritControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];