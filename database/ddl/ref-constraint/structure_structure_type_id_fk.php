<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_structure_type_id_fk',
    'table'       => 'structure',
    'rtable'      => 'structure_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'structure_type_pk',
    'columns'     => [
        'type_id' => 'id',
    ],
];

//@formatter:on
