<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheprofil_fiche_poste_id_fk',
    'table'       => 'ficheprofil',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_metier_pkey',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
