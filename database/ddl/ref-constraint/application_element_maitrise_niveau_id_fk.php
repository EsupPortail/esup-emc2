<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'application_element_maitrise_niveau_id_fk',
    'table'       => 'element_application_element',
    'rtable'      => 'element_niveau',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'maitrise_niveau_pk',
    'columns'     => [
        'niveau_id' => 'id',
    ],
];

//@formatter:on
