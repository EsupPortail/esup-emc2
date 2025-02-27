<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_formation_groupe_id_fk',
    'table'       => 'formation',
    'rtable'      => 'formation_groupe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'formation_groupe_pk',
    'columns'     => [
        'groupe_id' => 'id',
    ],
];

//@formatter:on
