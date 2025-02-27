<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_user_id_fk',
    'table'       => 'metier_metier',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
