<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ffc_createur_fk',
    'table'       => 'ficheposte_formation_retiree',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
