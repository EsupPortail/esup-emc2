<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_categorie_carriere_categorie_id_fk',
    'table'       => 'fichemetier_categorie',
    'rtable'      => 'carriere_categorie',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'categorie_pk',
    'columns'     => [
        'categorie_id' => 'id',
    ],
];

//@formatter:on
