<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_validation',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'entretien_id'  => [
            'name'        => 'entretien_id',
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
        'validation_id' => [
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
