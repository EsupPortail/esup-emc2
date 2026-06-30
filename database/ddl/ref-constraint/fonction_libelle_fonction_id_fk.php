<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fonction_libelle_fonction_id_fk',
    'table'       => 'fonction_libelle',
    'rtable'      => 'fonction',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fonction_pk',
    'columns'     => [
        'fonction_id' => 'id',
    ],
];

//@formatter:on
