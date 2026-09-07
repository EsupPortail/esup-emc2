<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_metier_correspondance_carriere_correspondance_id_fk',
    'table'       => 'metier_metier_correspondance',
    'rtable'      => 'carriere_correspondance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'correspondance_pk',
    'columns'     => [
        'correspondance_id' => 'id',
    ],
];

//@formatter:on
