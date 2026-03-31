<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_enquete_reponse_unicaen_enquete_instance_id_fk',
    'table'       => 'unicaen_enquete_reponse',
    'rtable'      => 'unicaen_enquete_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_enquete_instance_pk',
    'columns'     => [
        'instance_id' => 'id',
    ],
];

//@formatter:on
