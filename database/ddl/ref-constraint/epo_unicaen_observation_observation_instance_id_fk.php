<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epo_unicaen_observation_observation_instance_id_fk',
    'table'       => 'entretienprofessionnel_observation',
    'rtable'      => 'unicaen_observation_observation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_observation_observation_instance_pk',
    'columns'     => [
        'observation_id' => 'id',
    ],
];

//@formatter:on
