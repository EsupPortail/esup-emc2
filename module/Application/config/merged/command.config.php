<?php

use Application\Service\Bdd\BddFactory;
use Application\Command\UpdateBddCommand;
use Application\Command\UpdateDdlCommand;
use Unicaen\BddAdmin\Bdd;


return [
    'bjyauthorize' => [
        'guards' => [
        ],
    ],

    'laminas-cli' => [
        'commands' => [
            'update-bdd' => UpdateBddCommand::class,
            'update-ddl' => UpdateDdlCommand::class,
        ],
    ],

    'controllers'     => [
        'factories' => [
        ],
    ],
    'service_manager' => [
        'factories' => [
            Bdd::class => BddFactory::class,
        ],
        'aliases'   => [
            'bddDefault' => Bdd::class,
            'bddSource'  => Bdd::class,
        ],
    ],

    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators'     => [
        'factories' => [
        ],
    ],
    'view_helpers'  => [
        'invokables' => [
        ],
    ],
];

