<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_categorie_id_fk',
    'table'       => 'metier_metier',
    'rtable'      => 'carriere_categorie',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'categorie_id_uindex',
    'columns'     => [
        'categorie_id' => 'id',
    ],
];

//@formatter:on
