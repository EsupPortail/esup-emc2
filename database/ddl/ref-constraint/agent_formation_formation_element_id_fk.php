<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_formation_formation_element_id_fk',
    'table'       => 'agent_element_formation',
    'rtable'      => 'formation_element',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_element_pk',
    'columns'     => [
        'formation_element_id' => 'id',
    ],
];

//@formatter:on
