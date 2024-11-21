<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretien_professionnel_agent_c_individu_fk',
    'table'       => 'entretienprofessionnel',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'agent_pk',
    'columns'     => [
        'agent' => 'c_individu',
    ],
];

//@formatter:on
