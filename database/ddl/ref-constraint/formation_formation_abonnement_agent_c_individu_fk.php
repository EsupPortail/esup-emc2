<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_formation_abonnement_agent_c_individu_fk',
    'table'       => 'formation_formation_abonnement',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent_id' => 'c_individu',
    ],
];

//@formatter:on
