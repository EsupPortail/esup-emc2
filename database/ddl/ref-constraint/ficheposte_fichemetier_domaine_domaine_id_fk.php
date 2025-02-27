<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_fichemetier_domaine_domaine_id_fk',
    'table'       => 'ficheposte_fichemetier_domaine',
    'rtable'      => 'metier_domaine',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'domaine_pk',
    'columns'     => [
        'domaine_id' => 'id',
    ],
];

//@formatter:on
