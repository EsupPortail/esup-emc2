<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'expertise_destructeur_fk',
    'table'       => 'ficheposte_expertise',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
