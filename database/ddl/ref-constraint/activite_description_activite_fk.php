<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'activite_description_activite_fk',
    'table'       => 'activite_description',
    'rtable'      => 'activite',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'activite_pkey',
    'columns'     => [
        'activite_id' => 'id',
    ],
];

//@formatter:on
