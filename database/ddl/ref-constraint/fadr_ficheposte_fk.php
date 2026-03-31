<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fadr_ficheposte_fk',
    'table'       => 'ficheposte_activitedescription_retiree',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_metier_id_uindex',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
