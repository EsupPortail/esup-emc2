<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_tutorat_metier_id_fk',
    'table'       => 'agent_ccc_tutorat',
    'rtable'      => 'metier_metier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'metier_pkey',
    'columns'     => [
        'metier_id' => 'id',
    ],
];

//@formatter:on
