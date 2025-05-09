<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_utilisateur_user',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'unicaen_utilisateur_user_id_seq',
    'columns'     => [
        'display_name'         => [
            'name'        => 'display_name',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'email'                => [
            'name'        => 'email',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'id'                   => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'unicaen_utilisateur_user_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'last_role_id'         => [
            'name'        => 'last_role_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'password'             => [
            'name'        => 'password',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 128,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'application\'',
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'password_reset_token' => [
            'name'        => 'password_reset_token',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'state'                => [
            'name'        => 'state',
            'type'        => 'bool',
            'bdd-type'    => 'boolean',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'true',
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'username'             => [
            'name'        => 'username',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 255,
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
