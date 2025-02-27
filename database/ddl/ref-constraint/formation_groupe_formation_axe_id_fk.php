<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_groupe_formation_axe_id_fk',
    'table'       => 'formation_groupe',
    'rtable'      => 'formation_axe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'formation_axe_pk',
    'columns'     => [
        'axe_id' => 'id',
    ],
];

//@formatter:on
