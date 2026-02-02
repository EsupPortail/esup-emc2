<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'activite_element_activite_id_fk',
    'table'       => 'activite_element',
    'rtable'      => 'activite',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'activite_pk',
    'columns'     => [
        'activite_id' => 'id',
    ],
];

//@formatter:on
