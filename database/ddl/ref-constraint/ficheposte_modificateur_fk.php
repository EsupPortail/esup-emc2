<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_modificateur_fk',
    'table'       => 'ficheposte',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
