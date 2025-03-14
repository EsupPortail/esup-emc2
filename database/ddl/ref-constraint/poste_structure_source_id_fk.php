<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'poste_structure_source_id_fk',
    'table'       => 'poste',
    'rtable'      => 'structure',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'structure_pk',
    'columns'     => [
        'structure_id' => 'id',
    ],
];

//@formatter:on
