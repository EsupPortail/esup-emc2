<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'carriere_niveau_carriere_categorie_id_fk',
    'table'       => 'carriere_niveau',
    'rtable'      => 'carriere_categorie',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'categorie_pk',
    'columns'     => [
        'categorie_id' => 'id',
    ],
];

//@formatter:on
