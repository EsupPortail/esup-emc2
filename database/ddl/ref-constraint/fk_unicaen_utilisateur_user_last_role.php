<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fk_unicaen_utilisateur_user_last_role',
    'table'       => 'unicaen_utilisateur_user',
    'rtable'      => 'unicaen_utilisateur_role',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_role_pkey',
    'columns'     => [
        'last_role_id' => 'id',
    ],
];

//@formatter:on
