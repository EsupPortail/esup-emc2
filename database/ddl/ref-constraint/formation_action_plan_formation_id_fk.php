<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_action_plan_formation_id_fk',
    'table'       => 'formation_action_plan',
    'rtable'      => 'formation',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_pk',
    'columns'     => [
        'action_id' => 'id',
    ],
];

//@formatter:on
