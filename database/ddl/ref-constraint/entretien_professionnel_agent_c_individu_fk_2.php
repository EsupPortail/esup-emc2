<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretien_professionnel_agent_c_individu_fk_2',
    'table'       => 'entretienprofessionnel',
    'rtable'      => 'agent',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'agent_pk',
    'columns'     => [
        'responsable_id' => 'c_individu',
    ],
];

//@formatter:on
