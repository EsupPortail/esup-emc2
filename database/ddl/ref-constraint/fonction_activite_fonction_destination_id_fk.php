<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fonction_activite_fonction_destination_id_fk',
    'table'       => 'fonction_activite',
    'rtable'      => 'fonction_destination',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fonction_destination_pk',
    'columns'     => [
        'fonction_destination_id' => 'id',
    ],
];

//@formatter:on
