<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fcc_application_fk',
    'table'       => 'ficheposte_application_retiree',
    'rtable'      => 'element_application',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'application_informations_pkey',
    'columns'     => [
        'application_id' => 'id',
    ],
];

//@formatter:on
