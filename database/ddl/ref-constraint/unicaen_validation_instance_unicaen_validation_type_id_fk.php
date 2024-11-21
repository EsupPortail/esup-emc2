<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_validation_instance_unicaen_validation_type_id_fk',
    'table'       => 'unicaen_validation_instance',
    'rtable'      => 'unicaen_validation_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_validation_type_pk',
    'columns'     => [
        'type_id' => 'id',
    ],
];

//@formatter:on
