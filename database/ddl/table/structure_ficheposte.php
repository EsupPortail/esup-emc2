<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_ficheposte',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'ficheposte_id' => [
            'name'        => 'ficheposte_id',
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
        'structure_id'  => [
            'name'        => 'structure_id',
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
    ],
];

//@formatter:on
