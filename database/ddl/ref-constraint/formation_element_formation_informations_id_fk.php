<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_element_formation_informations_id_fk',
    'table'       => 'formation_element',
    'rtable'      => 'formation',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_pk',
    'columns'     => [
        'formation_id' => 'id',
    ],
];

//@formatter:on
