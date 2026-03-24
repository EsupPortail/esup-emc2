<?php


use EntretienProfessionnel\Command\RefreshProgressionCommand;
use EntretienProfessionnel\Command\RefreshProgressionCommandFactory;

return [
    'bjyauthorize' => [
        'guards' => [
        ],
    ],

    'laminas-cli' => [
        'commands' => [
            'entretien-professionnel:refresh-progression' => RefreshProgressionCommand::class,
        ],
    ],

    'controllers' => [
        'factories' => [
        ],
    ],
    'service_manager' => [
        'factories' => [
            RefreshProgressionCommand::class => RefreshProgressionCommandFactory::class,
        ],
    ],

    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
];

