<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_mail_mail',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'unicaen_mail_mail_id_seq',
    'columns'     => [
        'attachment_paths'       => [
            'name'        => 'attachment_paths',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 2056,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'copies'                 => [
            'name'        => 'copies',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'corps'                  => [
            'name'        => 'corps',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'date_envoi'             => [
            'name'        => 'date_envoi',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'destinataires'          => [
            'name'        => 'destinataires',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'destinataires_initials' => [
            'name'        => 'destinataires_initials',
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
        'id'                     => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'unicaen_mail_mail_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'log'                    => [
            'name'        => 'log',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'mots_clefs'             => [
            'name'        => 'mots_clefs',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'reply_to'               => [
            'name'        => 'reply_to',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'status_envoi'           => [
            'name'        => 'status_envoi',
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
        'sujet'                  => [
            'name'        => 'sujet',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
