<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_validations_ficheposte_id_fk',
    'table'       => 'ficheposte_validation',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_metier_pkey',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
