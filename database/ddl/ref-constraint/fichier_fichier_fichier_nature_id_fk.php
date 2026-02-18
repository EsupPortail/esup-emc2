<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichier_fichier_fichier_nature_id_fk',
    'table'       => 'unicaen_fichier_fichier',
    'rtable'      => 'unicaen_fichier_nature',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'fichier_nature_pk',
    'columns'     => [
        'nature' => 'id',
    ],
];

//@formatter:on
