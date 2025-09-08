<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichier_fichier_fichier_nature_id_fk',
    'table'       => 'fichier_fichier',
    'rtable'      => 'fichier_nature',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'fichier_nature_id_uindex',
    'columns'     => [
        'nature' => 'id',
    ],
];

//@formatter:on
