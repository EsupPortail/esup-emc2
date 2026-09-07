<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fadr_fichemetier_fk',
    'table'       => 'ficheposte_activitedescription_retiree',
    'rtable'      => 'fichemetier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_type_metier_pkey',
    'columns'     => [
        'fichemetier_id' => 'id',
    ],
];

//@formatter:on
