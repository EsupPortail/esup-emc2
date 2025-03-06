<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'uov_unicaen_validation_instance_id_fk',
    'table'       => 'unicaen_observation_observation_validation',
    'rtable'      => 'unicaen_validation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_validation_instance_id_uindex',
    'columns'     => [
        'validation_id' => 'id',
    ],
];

//@formatter:on
