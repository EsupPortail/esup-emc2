<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_demande_externe_etat_unicaen_etat_instance_id_fk',
    'table'       => 'formation_demande_externe_etat',
    'rtable'      => 'unicaen_etat_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_etat_instance_pk',
    'columns'     => [
        'etat_id' => 'id',
    ],
];

//@formatter:on
