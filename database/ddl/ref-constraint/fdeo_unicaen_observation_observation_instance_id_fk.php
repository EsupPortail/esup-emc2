<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fdeo_unicaen_observation_observation_instance_id_fk',
    'table'       => 'formation_demande_externe_observation',
    'rtable'      => 'unicaen_observation_observation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_observation_observation_instance_pk',
    'columns'     => [
        'observation_instance_id' => 'id',
    ],
];

//@formatter:on
