<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'autoform_validation_reponse_autoform_validation_id_fk',
    'table'       => 'unicaen_autoform_validation_reponse',
    'rtable'      => 'unicaen_autoform_validation',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'validation_id_uindex',
    'columns'     => [
        'validation' => 'id',
    ],
];

//@formatter:on
