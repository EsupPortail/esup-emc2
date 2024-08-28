<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_indicateur_categorie',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => 'unicaen_indicateur_categorie_id_seq',
    'columns'     => [
        'code'        => [
            'name'        => 'code',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'description' => [
            'name'        => 'description',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
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
            'default'     => 'nextval(\'unicaen_indicateur_categorie_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'libelle'     => [
            'name'        => 'libelle',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1024,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'ordre'       => [
            'name'        => 'ordre',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => '9999',
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
