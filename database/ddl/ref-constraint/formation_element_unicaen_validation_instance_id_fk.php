<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_element_unicaen_validation_instance_id_fk',
    'table'       => 'formation_element',
    'rtable'      => 'unicaen_validation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'unicaen_validation_instance_pk',
    'columns'     => [
        'validation_id' => 'id',
    ],
];

//@formatter:on
