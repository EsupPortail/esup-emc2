<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'table_name',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => 'table_name_id_seq',
    'columns'     => [
        'id' => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'bigint',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'table_name_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
