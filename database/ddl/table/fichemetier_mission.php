<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_mission',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'fichemetier_mission_id_seq',
    'columns'     => [
        'fichemetier_id' => [
            'name'        => 'fichemetier_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'id'             => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'fichemetier_mission_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'mission_id'     => [
            'name'        => 'mission_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'ordre'          => [
            'name'        => 'ordre',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
