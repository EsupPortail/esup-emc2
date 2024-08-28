<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance_formation_session_parametre_null_fk',
    'table'       => 'formation_instance',
    'rtable'      => 'formation_session_parametre',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'formation_session_parametre_pk',
    'columns'     => [
        'parametre_id' => 'id',
    ],
];

//@formatter:on
