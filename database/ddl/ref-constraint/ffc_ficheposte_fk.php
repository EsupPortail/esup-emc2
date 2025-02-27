<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ffc_ficheposte_fk',
    'table'       => 'ficheposte_formation_retiree',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_metier_pkey',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
