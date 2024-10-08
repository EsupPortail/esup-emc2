<?php

namespace Formation;

use Formation\Controller\PresenceController;
use Formation\Controller\PresenceControllerFactory;
use Formation\Provider\Privilege\FormationinstancepresencePrivileges;
use Formation\Service\Presence\PresenceService;
use Formation\Service\Presence\PresenceServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => PresenceController::class,
                    'action' => [
                        'renseigner-presences',
                    ],
                    'privileges' => [
                        FormationinstancepresencePrivileges::FORMATIONINSTANCEPRESENCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => PresenceController::class,
                    'action' => [
                        'toggle-presence',
                        'toggle-presences',
                        'toggle-toutes-presences',
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
            'formation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/formation',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'session' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/session',
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                    'renseigner-presences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/renseigner-presences/:session',
                            'defaults' => [
                                /** @see PresenceController::renseignerPresencesAction() */
                                'controller' => PresenceController::class,
                                'action'     => 'renseigner-presences',
                            ],
                        ],
                    ],
                    'toggle-presence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/toggle-presence/:journee/:inscription',
                            'defaults' => [
                                /** @see PresenceController::togglePresenceAction() */
                                'controller' => PresenceController::class,
                                'action'     => 'toggle-presence',
                            ],
                        ],
                    ],
                    'toggle-presences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/toggle-presences/:mode/:inscription',
                            'defaults' => [
                                /** @see PresenceController::togglePresencesAction() */
                                'controller' => PresenceController::class,
                                'action'     => 'toggle-presences',
                            ],
                        ],
                    ],
                    'toggle-toutes-presences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/toggle-toutes-presences/:mode/:session',
                            'defaults' => [
                                /** @see PresenceController::toggleToutesPresencesAction() */
                                'controller' => PresenceController::class,
                                'action'     => 'toggle-toutes-presences',
                            ],
                        ],
                    ],
                ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            PresenceService::class => PresenceServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            PresenceController::class => PresenceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];