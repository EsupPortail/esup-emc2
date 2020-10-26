<?php

namespace Formation;

use Formation\Provider\Privilege\FormationinstancepresencePrivileges;
use Formation\Controller\FormationInstancePresenceController;
use Formation\Controller\FormationInstancePresenceControllerFactory;
use Formation\Service\FormationInstancePresence\FormationInstancePresenceService;
use Formation\Service\FormationInstancePresence\FormationInstancePresenceServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstancePresenceController::class,
                    'action' => [
                        'renseigner-presences',
                    ],
                    'privileges' => [
                        FormationinstancepresencePrivileges::FORMATIONINSTANCEPRESENCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => FormationInstancePresenceController::class,
                    'action' => [
                        'toggle-presence',
                        'toggle-presences',
                    ],
                    'privileges' => [
                        FormationinstancepresencePrivileges::FORMATIONINSTANCEPRESENCE_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation-instance' => [
                'child_routes' => [
                    'renseigner-presences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/renseigner-presences/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstancePresenceController::class,
                                'action'     => 'renseigner-presences',
                            ],
                        ],
                    ],
                    'toggle-presence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/toggle-presence/:journee/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstancePresenceController::class,
                                'action'     => 'toggle-presence',
                            ],
                        ],
                    ],
                    'toggle-presences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/toggle-presences/:mode/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstancePresenceController::class,
                                'action'     => 'toggle-presences',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationInstancePresenceService::class => FormationInstancePresenceServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationInstancePresenceController::class => FormationInstancePresenceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];