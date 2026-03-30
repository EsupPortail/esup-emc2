<?php


use EntretienProfessionnel\Command\RefreshProgressionStructureCommand;
use EntretienProfessionnel\Command\RefreshProgressionStructureCommandFactory;

return [
    'bjyauthorize' => [
        'guards' => [
        ],
    ],

    'laminas-cli' => [
        'commands' => [
            'entretien-professionnel:refresh-progression-structure' => RefreshProgressionStructureCommand::class,
        ],
    ],

    'controllers' => [
        'factories' => [
        ],
    ],
    'service_manager' => [
        'factories' => [
            RefreshProgressionStructureCommand::class => RefreshProgressionStructureCommandFactory::class,
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

