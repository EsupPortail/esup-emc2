<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_inscription_fichier_formation_inscription_id_fk',
    'table'       => 'formation_inscription_fichier',
    'rtable'      => 'formation_inscription',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_inscription_pk',
    'columns'     => [
        'inscription_id' => 'id',
    ],
];

//@formatter:on
