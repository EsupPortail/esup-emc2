<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_createur_fk',
    'table'       => 'ficheposte',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
