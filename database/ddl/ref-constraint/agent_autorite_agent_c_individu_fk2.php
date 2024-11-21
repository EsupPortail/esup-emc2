<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_autorite_agent_c_individu_fk2',
    'table'       => 'agent_hierarchie_autorite',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'agent_pk',
    'columns'     => [
        'autorite_id' => 'c_individu',
    ],
];

//@formatter:on
