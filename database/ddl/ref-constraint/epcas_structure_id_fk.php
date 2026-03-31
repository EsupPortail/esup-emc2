<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epcas_structure_id_fk',
    'table'       => 'entretienprofessionnel_campagne_agent_statut',
    'rtable'      => 'structure',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'structure_pk',
    'columns'     => [
        'structure_id' => 'id',
    ],
];

//@formatter:on
