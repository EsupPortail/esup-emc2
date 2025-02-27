<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ffc_formation_fk',
    'table'       => 'ficheposte_formation_retiree',
    'rtable'      => 'formation',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_pk',
    'columns'     => [
        'formation_id' => 'id',
    ],
];

//@formatter:on
