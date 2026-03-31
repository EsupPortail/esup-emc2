<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'competence_theme__fk',
    'table'       => 'element_competence',
    'rtable'      => 'element_competence_theme',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'competence_theme_id_uindex',
    'columns'     => [
        'theme_id' => 'id',
    ],
];

//@formatter:on
