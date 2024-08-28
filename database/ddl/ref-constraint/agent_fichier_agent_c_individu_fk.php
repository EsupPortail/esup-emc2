<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_fichier_agent_c_individu_fk',
    'table'       => 'agent_fichier',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent' => 'c_individu',
    ],
];

//@formatter:on
