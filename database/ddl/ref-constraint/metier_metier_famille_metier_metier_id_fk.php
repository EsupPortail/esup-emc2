<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_metier_famille_metier_metier_id_fk',
    'table'       => 'metier_metier_famille',
    'rtable'      => 'metier_metier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'metier_id_uindex',
    'columns'     => [
        'metier_id' => 'id',
    ],
];

//@formatter:on
