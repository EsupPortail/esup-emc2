<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_etat_fichemetier_id_fk',
    'table'       => 'ficheposte_etat',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_metier_id_uindex',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
