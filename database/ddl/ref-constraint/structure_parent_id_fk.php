<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_parent_id_fk',
    'table'       => 'structure',
    'rtable'      => 'structure',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'structure_pk',
    'columns'     => [
        'parent_id' => 'id',
    ],
];

//@formatter:on
