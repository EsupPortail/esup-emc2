<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'inscription_etat_inscription_id_fk',
    'table'       => 'formation_inscription_etat',
    'rtable'      => 'formation_inscription',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_inscription_pk',
    'columns'     => [
        'inscription_id' => 'id',
    ],
];

//@formatter:on
