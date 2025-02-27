<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fapc_formation_plan_formation_id_fk',
    'table'       => 'formation_action_cout_previsionnel',
    'rtable'      => 'formation_plan_formation',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_plan_formation_pk',
    'columns'     => [
        'plan_id' => 'id',
    ],
];

//@formatter:on
