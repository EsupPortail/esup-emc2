<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_inscription_formation_instance_id_fk',
    'table'       => 'formation_inscription',
    'rtable'      => 'formation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_instance_pk',
    'columns'     => [
        'session_id' => 'id',
    ],
];

//@formatter:on
