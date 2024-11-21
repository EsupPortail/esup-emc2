<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fk_evenement_instance_type',
    'table'       => 'unicaen_evenement_instance',
    'rtable'      => 'unicaen_evenement_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'pk_evenement_type',
    'columns'     => [
        'type_id' => 'id',
    ],
];

//@formatter:on
