<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fcc_destructeur_fk',
    'table'       => 'ficheposte_application_retiree',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
