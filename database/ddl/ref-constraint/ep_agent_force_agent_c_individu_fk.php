<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ep_agent_force_agent_c_individu_fk',
    'table'       => 'entretienprofessionnel_agent_force',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent_id' => 'c_individu',
    ],
];

//@formatter:on
