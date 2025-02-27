<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_validations_unicaen_validation_instance_id_fk',
    'table'       => 'ficheposte_validation',
    'rtable'      => 'unicaen_validation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_validation_instance_pk',
    'columns'     => [
        'validation_id' => 'id',
    ],
];

//@formatter:on
