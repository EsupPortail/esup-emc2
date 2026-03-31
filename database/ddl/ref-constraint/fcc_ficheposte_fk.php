<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fcc_ficheposte_fk',
    'table'       => 'ficheposte_application_retiree',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_metier_id_uindex',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
