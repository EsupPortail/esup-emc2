<?php

namespace Formation;

use Formation\Controller\SessionParametreController;
use Formation\Controller\SessionParametreControllerFactory;
use Formation\Form\SessionParametre\SessionParametreForm;
use Formation\Form\SessionParametre\SessionParametreFormFactory;
use Formation\Form\SessionParametre\SessionParametreHydrator;
use Formation\Form\SessionParametre\SessionParametreHydratorFactory;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Service\SessionParametre\SessionParametreService;
use Formation\Service\SessionParametre\SessionParametreServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => SessionParametreController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'session-parametre' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/session-parametre/:session',
                            'defaults' => [
                                'controller' => SessionParametreController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            SessionParametreService::class => SessionParametreServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            SessionParametreController::class => SessionParametreControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            SessionParametreForm::class => SessionParametreFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            SessionParametreHydrator::class => SessionParametreHydratorFactory::class,
        ],
    ]

];