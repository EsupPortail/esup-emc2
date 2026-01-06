<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_responsable_structure_id_fk',
    'table'       => 'structure_responsable',
    'rtable'      => 'structure',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'structure_pk',
    'columns'     => [
        'structure_id' => 'id',
    ],
];

//@formatter:on
