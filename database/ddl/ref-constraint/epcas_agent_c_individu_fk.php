<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epcas_agent_c_individu_fk',
    'table'       => 'entretienprofessionnel_campagne_agent_statut',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent_id' => 'c_individu',
    ],
];

//@formatter:on
