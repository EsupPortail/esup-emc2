<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichier_nature',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => 'fichier_nature_id_seq',
    'columns'     => [
        'code'        => [
            'name'        => 'code',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 64,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'description' => [
            'name'        => 'description',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 2048,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'id'          => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'fichier_nature_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'libelle'     => [
            'name'        => 'libelle',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
