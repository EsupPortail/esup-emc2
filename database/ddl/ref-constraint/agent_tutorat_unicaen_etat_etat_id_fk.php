<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_tutorat_unicaen_etat_etat_id_fk',
    'table'       => 'agent_ccc_tutorat',
    'rtable'      => 'unicaen_etat_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'unicaen_etat_instance_pk',
    'columns'     => [
        'etat_id' => 'id',
    ],
];

//@formatter:on
