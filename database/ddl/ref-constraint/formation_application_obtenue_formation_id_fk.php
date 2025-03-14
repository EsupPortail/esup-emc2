<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_application_obtenue_formation_id_fk',
    'table'       => 'formation_obtenue_application',
    'rtable'      => 'formation',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_pk',
    'columns'     => [
        'formation_id' => 'id',
    ],
];

//@formatter:on
