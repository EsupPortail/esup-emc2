<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'carriere_correspondance_carriere_correspondance_type_null_fk',
    'table'       => 'carriere_correspondance',
    'rtable'      => 'carriere_correspondance_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'carriere_correspondance_type_pk',
    'columns'     => [
        'type_id' => 'id',
    ],
];

//@formatter:on
