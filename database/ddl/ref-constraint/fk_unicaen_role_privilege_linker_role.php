<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fk_unicaen_role_privilege_linker_role',
    'table'       => 'unicaen_privilege_privilege_role_linker',
    'rtable'      => 'unicaen_utilisateur_role',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_utilisateur_role_pkey',
    'columns'     => [
        'role_id' => 'id',
    ],
];

//@formatter:on
