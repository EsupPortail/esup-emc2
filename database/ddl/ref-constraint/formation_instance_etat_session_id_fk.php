<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance_etat_session_id_fk',
    'table'       => 'formation_session_etat',
    'rtable'      => 'formation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_instance_pk',
    'columns'     => [
        'session_id' => 'id',
    ],
];

//@formatter:on
