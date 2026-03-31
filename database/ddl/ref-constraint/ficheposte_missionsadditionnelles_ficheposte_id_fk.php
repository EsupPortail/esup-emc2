<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_missionsadditionnelles_ficheposte_id_fk',
    'table'       => 'ficheposte_missionsadditionnelles',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_metier_id_uindex',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
