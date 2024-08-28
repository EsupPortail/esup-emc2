<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_stageobs_unicaen_etat_etat_id_fk',
    'table'       => 'agent_ccc_stageobs',
    'rtable'      => 'unicaen_etat_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'unicaen_etat_instance_pkey',
    'columns'     => [
        'etat_id' => 'id',
    ],
];

//@formatter:on
