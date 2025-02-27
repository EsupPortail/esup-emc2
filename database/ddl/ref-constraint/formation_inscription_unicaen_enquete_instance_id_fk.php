<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_inscription_unicaen_enquete_instance_id_fk',
    'table'       => 'formation_inscription',
    'rtable'      => 'unicaen_enquete_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'unicaen_enquete_instance_pk',
    'columns'     => [
        'enquete_instance_id' => 'id',
    ],
];

//@formatter:on
