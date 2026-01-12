<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_carriere_affectation_structure_id_fk',
    'table'       => 'agent_carriere_affectation',
    'rtable'      => 'structure',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'structure_pk',
    'columns'     => [
        'structure_id' => 'id',
    ],
];

//@formatter:on
