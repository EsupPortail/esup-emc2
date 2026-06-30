<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_niveau_carriere_id_fkey',
    'table'       => 'fichemetier',
    'rtable'      => 'carriere_niveau',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'niveau_definition_pk',
    'columns'     => [
        'niveau_carriere_id' => 'id',
    ],
];

//@formatter:on
