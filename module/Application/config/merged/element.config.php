<?php

namespace Application;

use Application\Controller\ElementController;
use Application\Controller\ElementControllerFactory;
use Application\Provider\Privilege\ApplicationPrivileges;
use Application\Provider\Privilege\CompetencePrivileges;
use Formation\Provider\Privilege\FormationPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ElementController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_AFFICHER,
                        CompetencePrivileges::COMPETENCE_AFFICHER,
                        FormationPrivileges::FORMATION_AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'element' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/element',
                    'defaults' => [
                        'controller' => ElementController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:type/:id',
                            'defaults' => [
                                'controller' => ElementController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
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
            ElementController::class => ElementControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];