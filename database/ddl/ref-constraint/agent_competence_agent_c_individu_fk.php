<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_competence_agent_c_individu_fk',
    'table'       => 'agent_element_competence',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent_id' => 'c_individu',
    ],
];

//@formatter:on
