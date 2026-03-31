<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_carriere_categorie_id_fk',
    'table'       => 'fichemetier',
    'rtable'      => 'carriere_categorie',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'categorie_pk',
    'columns'     => [
        'categorie_id' => 'id',
    ],
];

//@formatter:on
