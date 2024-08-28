<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fcc_ficheposte_fk',
    'table'       => 'ficheposte_application_retiree',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'ficheposte_pkey',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
