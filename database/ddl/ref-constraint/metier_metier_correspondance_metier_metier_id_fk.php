<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_metier_correspondance_metier_metier_id_fk',
    'table'       => 'metier_metier_correspondance',
    'rtable'      => 'metier_metier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'metier_pkey',
    'columns'     => [
        'metier_id' => 'id',
    ],
];

//@formatter:on
