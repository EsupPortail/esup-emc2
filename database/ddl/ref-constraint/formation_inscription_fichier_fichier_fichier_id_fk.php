<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_inscription_fichier_fichier_fichier_id_fk',
    'table'       => 'formation_inscription_fichier',
    'rtable'      => 'fichier_fichier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fichier_fichier_pk',
    'columns'     => [
        'fichier_id' => 'id',
    ],
];

//@formatter:on
