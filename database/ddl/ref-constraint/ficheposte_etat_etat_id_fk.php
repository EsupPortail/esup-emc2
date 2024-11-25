<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_etat_etat_id_fk',
    'table'       => 'ficheposte_etat',
    'rtable'      => 'unicaen_etat_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_etat_instance_id_index',
    'columns'     => [
        'etat_id' => 'id',
    ],
];

//@formatter:on