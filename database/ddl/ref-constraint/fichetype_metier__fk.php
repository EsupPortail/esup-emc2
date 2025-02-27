<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichetype_metier__fk',
    'table'       => 'fichemetier',
    'rtable'      => 'metier_metier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'metier_pkey',
    'columns'     => [
        'metier_id' => 'id',
    ],
];

//@formatter:on
