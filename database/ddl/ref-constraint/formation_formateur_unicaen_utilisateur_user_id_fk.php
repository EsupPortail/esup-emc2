<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_formateur_unicaen_utilisateur_user_id_fk',
    'table'       => 'formation_formateur',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'user_id' => 'id',
    ],
];

//@formatter:on
