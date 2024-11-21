<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_observateur_structure_id_fk',
    'table'       => 'structure_observateur',
    'rtable'      => 'structure',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'structure_pk',
    'columns'     => [
        'structure_id' => 'id',
    ],
];

//@formatter:on
