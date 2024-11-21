<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_enquete_instance_unicaen_enquete_enquete_id_fk',
    'table'       => 'unicaen_enquete_instance',
    'rtable'      => 'unicaen_enquete_enquete',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_enquete_enquete_pk',
    'columns'     => [
        'enquete_id' => 'id',
    ],
];

//@formatter:on
