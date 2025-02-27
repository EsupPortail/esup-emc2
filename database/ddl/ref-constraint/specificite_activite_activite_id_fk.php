<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'specificite_activite_activite_id_fk',
    'table'       => 'ficheposte_activite_specifique',
    'rtable'      => 'activite',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'activite_pkey',
    'columns'     => [
        'activite_id' => 'id',
    ],
];

//@formatter:on
