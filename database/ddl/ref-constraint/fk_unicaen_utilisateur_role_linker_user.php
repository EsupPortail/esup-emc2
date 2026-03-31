<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fk_unicaen_utilisateur_role_linker_user',
    'table'       => 'unicaen_utilisateur_role_linker',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'user_id' => 'id',
    ],
];

//@formatter:on
