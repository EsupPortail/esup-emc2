<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'carriere_corps',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'categorie'             => [
            'name'        => 'categorie',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 10,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'code'                  => [
            'name'        => 'code',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 10,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'created_on'            => [
            'name'        => 'created_on',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => FALSE,
            'default'     => '(\'now\'::text)::timestamp(0) without time zone',
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'd_fermeture'           => [
            'name'        => 'd_fermeture',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'd_ouverture'           => [
            'name'        => 'd_ouverture',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'deleted_on'            => [
            'name'        => 'deleted_on',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'id'                    => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'bigint',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'id_orig'               => [
            'name'        => 'id_orig',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'lib_court'             => [
            'name'        => 'lib_court',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 20,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'lib_long'              => [
            'name'        => 'lib_long',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 200,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'niveau'                => [
            'name'        => 'niveau',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'niveaux_id'            => [
            'name'        => 'niveaux_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'source_id'             => [
            'name'        => 'source_id',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 128,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'superieur_as_autorite' => [
            'name'        => 'superieur_as_autorite',
            'type'        => 'bool',
            'bdd-type'    => 'boolean',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'false',
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'updated_on'            => [
            'name'        => 'updated_on',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 13,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on