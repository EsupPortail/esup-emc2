<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_demande_externe_validation_id2_fk',
    'table'       => 'formation_demande_externe_validation',
    'rtable'      => 'unicaen_validation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_validation_instance_pk',
    'columns'     => [
        'validation_id' => 'id',
    ],
];

//@formatter:on
