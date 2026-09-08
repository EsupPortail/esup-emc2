<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_formation_agent_c_individu_fk',
    'table'       => 'agent_element_formation',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent_id' => 'c_individu',
    ],
];

//@formatter:on
