<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fcc_modificateur_fk',
    'table'       => 'ficheposte_application_retiree',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
