<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'activite_application_activite_id_fk',
    'table'       => 'activite_application',
    'rtable'      => 'activite',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'activite_pkey',
    'columns'     => [
        'activite_id' => 'id',
    ],
];

//@formatter:on
