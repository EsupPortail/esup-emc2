<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_formateur_session_formation_instance_id_fk',
    'table'       => 'formation_formateur_session',
    'rtable'      => 'formation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_instance_pk',
    'columns'     => [
        'session_id' => 'id',
    ],
];

//@formatter:on
