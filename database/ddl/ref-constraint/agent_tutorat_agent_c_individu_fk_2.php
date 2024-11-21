<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_tutorat_agent_c_individu_fk_2',
    'table'       => 'agent_ccc_tutorat',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'agent_pk',
    'columns'     => [
        'cible_id' => 'c_individu',
    ],
];

//@formatter:on
