<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_fichier',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'agent'   => [
            'name'        => 'agent',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 40,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'fichier' => [
            'name'        => 'fichier',
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
    ],
];

//@formatter:on
