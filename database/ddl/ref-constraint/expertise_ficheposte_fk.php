<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'expertise_ficheposte_fk',
    'table'       => 'ficheposte_expertise',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_metier_pkey',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
