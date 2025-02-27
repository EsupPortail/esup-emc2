<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance_formation_id_fk',
    'table'       => 'formation_instance',
    'rtable'      => 'formation',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_pk',
    'columns'     => [
        'formation_id' => 'id',
    ],
];

//@formatter:on
