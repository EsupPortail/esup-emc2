<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance_presence_formation_instance_journee_id_fk',
    'table'       => 'formation_presence',
    'rtable'      => 'formation_seance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_instance_journee_pk',
    'columns'     => [
        'journee_id' => 'id',
    ],
];

//@formatter:on
