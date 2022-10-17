<?php

namespace Formation;

use Formation\Controller\SeanceController;
use Formation\Controller\SeanceControllerFactory;
use Formation\Form\Seance\SeanceForm;
use Formation\Form\Seance\SeanceFormFactory;
use Formation\Form\Seance\SeanceHydrator;
use Formation\Form\Seance\SeanceHydratorFactory;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Seance\SeanceServiceFactory;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => SeanceController::class,
                    'action' => [
                        'ajouter-journee',
                        'modifier-journee',
                        'historiser-journee',
                        'restaurer-journee',
                        'supprimer-journee',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_GERER_SEANCE,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation-instance' => [
                'child_routes' => [
                    'ajouter-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-journee/:formation-instance',
                            'defaults' => [
                                'controller' => SeanceController::class,
                                'action'     => 'ajouter-journee',
                            ],
                        ],
                    ],
                    'modifier-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-journee/:journee',
                            'defaults' => [
                                'controller' => SeanceController::class,
                                'action'     => 'modifier-journee',
                            ],
                        ],
                    ],
                    'historiser-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-journee/:journee',
                            'defaults' => [
                                'controller' => SeanceController::class,
                                'action'     => 'historiser-journee',
                            ],
                        ],
                    ],
                    'restaurer-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-journee/:journee',
                            'defaults' => [
                                'controller' => SeanceController::class,
                                'action'     => 'restaurer-journee',
                            ],
                        ],
                    ],
                    'supprimer-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-journee/:journee',
                            'defaults' => [
                                'controller' => SeanceController::class,
                                'action'     => 'supprimer-journee',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            SeanceService::class => SeanceServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            SeanceController::class => SeanceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            SeanceForm::class => SeanceFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            SeanceHydrator::class => SeanceHydratorFactory::class,
        ],
    ]

];