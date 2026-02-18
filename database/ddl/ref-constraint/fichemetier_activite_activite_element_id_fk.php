<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_activite_activite_element_id_fk',
    'table'       => 'fichemetier_activite',
    'rtable'      => 'activite_element',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'activite_element_pk',
    'columns'     => [
        'activite_element_id' => 'id',
    ],
];

//@formatter:on
