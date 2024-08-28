<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_agent_force_agent_c_individu_fk',
    'table'       => 'structure_agent_force',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent_id' => 'c_individu',
    ],
];

//@formatter:on
