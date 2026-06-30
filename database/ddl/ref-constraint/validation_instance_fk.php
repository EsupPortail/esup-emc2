<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'validation_instance_fk',
    'table'       => 'unicaen_autoform_validation',
    'rtable'      => 'unicaen_autoform_formulaire_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'autoform_formulaire_instance_pk',
    'columns'     => [
        'instance' => 'id',
    ],
];

//@formatter:on
