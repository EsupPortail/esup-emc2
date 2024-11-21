<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_missionsadditionnelles_ficheposte_id_fk',
    'table'       => 'ficheposte_missionsadditionnelles',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'ficheposte_pkey',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
