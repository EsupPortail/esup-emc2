<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'uov_observation_observation_id_fk',
    'table'       => 'unicaen_observation_observation_validation',
    'rtable'      => 'unicaen_observation_observation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_observation_observation_instance_pk',
    'columns'     => [
        'observation_instance_id' => 'id',
    ],
];

//@formatter:on
