<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_validation_unicaen_validation_instance_i',
    'table'       => 'entretienprofessionnel_validation',
    'rtable'      => 'unicaen_validation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_validation_instance_id_uindex',
    'columns'     => [
        'validation_id' => 'id',
    ],
];

//@formatter:on
