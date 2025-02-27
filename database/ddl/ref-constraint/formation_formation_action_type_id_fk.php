<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_formation_action_type_id_fk',
    'table'       => 'formation',
    'rtable'      => 'formation_action_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'formation_action_type_pk',
    'columns'     => [
        'action_type_id' => 'id',
    ],
];

//@formatter:on
