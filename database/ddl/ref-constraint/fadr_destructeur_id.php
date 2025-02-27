<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fadr_destructeur_id',
    'table'       => 'ficheposte_activitedescription_retiree',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
