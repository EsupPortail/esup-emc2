<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_famille_id_fkey',
    'table'       => 'fichemetier',
    'rtable'      => 'metier_familleprofessionnelle',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'metier_famille_pk',
    'columns'     => [
        'famille_id' => 'id',
    ],
];

//@formatter:on
