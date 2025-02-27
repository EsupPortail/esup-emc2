<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_seance_formation_lieu_id_fk',
    'table'       => 'formation_seance',
    'rtable'      => 'formation_lieu',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'formation_lieu_pk',
    'columns'     => [
        'lieu_id' => 'id',
    ],
];

//@formatter:on
