<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'application_element_application_informations_id_fk',
    'table'       => 'element_application_element',
    'rtable'      => 'element_application',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'application_informations_pkey',
    'columns'     => [
        'application_id' => 'id',
    ],
];

//@formatter:on
