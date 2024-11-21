<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_observation_observation_validation',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'observation_instance_id' => [
            'name'        => 'observation_instance_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'validation_id'           => [
            'name'        => 'validation_id',
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
    ],
];

//@formatter:on
