<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_inscription_fichier',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'fichier_id'     => [
            'name'        => 'fichier_id',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 13,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'inscription_id' => [
            'name'        => 'inscription_id',
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
