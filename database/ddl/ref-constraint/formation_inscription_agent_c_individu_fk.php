<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_inscription_agent_c_individu_fk',
    'table'       => 'formation_inscription',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent_id' => 'c_individu',
    ],
];

//@formatter:on
