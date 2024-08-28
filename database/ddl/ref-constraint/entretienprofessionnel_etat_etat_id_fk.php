<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_etat_etat_id_fk',
    'table'       => 'entretienprofessionnel_etat',
    'rtable'      => 'unicaen_etat_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_etat_instance_pkey',
    'columns'     => [
        'etat_id' => 'id',
    ],
];

//@formatter:on
