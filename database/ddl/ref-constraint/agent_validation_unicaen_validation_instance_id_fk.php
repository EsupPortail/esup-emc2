<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_validation_unicaen_validation_instance_id_fk',
    'table'       => 'agent_validation',
    'rtable'      => 'unicaen_validation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_validation_instance_pk',
    'columns'     => [
        'validation_instance_id' => 'id',
    ],
];

//@formatter:on
