<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_autoform_validation_reponse',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'id'         => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'autoform_validation_reponse_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'reponse'    => [
            'name'        => 'reponse',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'validation' => [
            'name'        => 'validation',
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
        'value'      => [
            'name'        => 'value',
            'type'        => 'bool',
            'bdd-type'    => 'boolean',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'false',
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
