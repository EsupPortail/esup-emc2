<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_fichemetier_fichemetier_id_fk',
    'table'       => 'ficheposte_fichemetier',
    'rtable'      => 'fichemetier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'fiche_type_metier_pkey',
    'columns'     => [
        'fiche_type' => 'id',
    ],
];

//@formatter:on
