<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_inscription_etat_etat_id_fk',
    'table'       => 'formation_inscription_etat',
    'rtable'      => 'unicaen_etat_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_etat_instance_pk',
    'columns'     => [
        'etat_id' => 'id',
    ],
];

//@formatter:on
