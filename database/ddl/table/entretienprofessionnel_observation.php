<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_observation',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => 'entretienprofessionnel_observation_id_seq',
    'columns'     => [
        'entretien_id'   => [
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
        'observation_id' => [
            'name'        => 'observation_id',
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
