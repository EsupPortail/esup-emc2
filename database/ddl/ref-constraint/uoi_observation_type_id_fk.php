<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'uoi_observation_type_id_fk',
    'table'       => 'unicaen_observation_observation_instance',
    'rtable'      => 'unicaen_observation_observation_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_observation_observation_type_pk',
    'columns'     => [
        'type_id' => 'id',
    ],
];

//@formatter:on
