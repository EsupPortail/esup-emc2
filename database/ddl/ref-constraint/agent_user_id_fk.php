<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_user_id_fk',
    'table'       => 'agent',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'user_pkey',
    'columns'     => [
        'utilisateur_id' => 'id',
    ],
];

//@formatter:on
