<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fk_unicaen_role_privilege_linker_privilege',
    'table'       => 'unicaen_privilege_privilege_role_linker',
    'rtable'      => 'unicaen_privilege_privilege',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_privilege_privilege_pkey',
    'columns'     => [
        'privilege_id' => 'id',
    ],
];

//@formatter:on
