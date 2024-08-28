<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fk_unicaen_utilisateur_role_linker_role',
    'table'       => 'unicaen_utilisateur_role_linker',
    'rtable'      => 'unicaen_utilisateur_role',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_role_pkey',
    'columns'     => [
        'role_id' => 'id',
    ],
];

//@formatter:on
