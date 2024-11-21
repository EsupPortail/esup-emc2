<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ep_agent_force_sansobligation_agent_c_individu_fkx',
    'table'       => 'entretienprofessionnel_agent_force_sansobligation',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent_id' => 'c_individu',
    ],
];

//@formatter:on
