<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fadr_modificateur_fk',
    'table'       => 'ficheposte_activitedescription_retiree',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
