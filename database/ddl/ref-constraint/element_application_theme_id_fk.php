<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'element_application_theme_id_fk',
    'table'       => 'element_application',
    'rtable'      => 'element_application_theme',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'application_groupe_id_uindex',
    'columns'     => [
        'theme_id' => 'id',
    ],
];

//@formatter:on
