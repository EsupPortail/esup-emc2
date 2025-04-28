<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_autoform_champ_unicaen_autoform_element_code_fk',
    'table'       => 'unicaen_autoform_champ',
    'rtable'      => 'unicaen_autoform_champ_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_autoform_champ_type_code_uindex',
    'columns'     => [
        'element' => 'code',
    ],
];

//@formatter:on
