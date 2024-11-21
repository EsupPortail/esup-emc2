<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_carriere_mobilite_agent_c_individu_fk',
    'table'       => 'agent_carriere_mobilite',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent_id' => 'c_individu',
    ],
];

//@formatter:on
