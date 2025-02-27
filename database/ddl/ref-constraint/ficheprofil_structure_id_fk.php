<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheprofil_structure_id_fk',
    'table'       => 'ficheprofil',
    'rtable'      => 'structure',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'structure_pk',
    'columns'     => [
        'structure_id' => 'id',
    ],
];

//@formatter:on
