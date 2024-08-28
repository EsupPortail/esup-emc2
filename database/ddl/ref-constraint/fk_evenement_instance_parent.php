<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fk_evenement_instance_parent',
    'table'       => 'unicaen_evenement_instance',
    'rtable'      => 'unicaen_evenement_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'pk_evenement_instance',
    'columns'     => [
        'parent_id' => 'id',
    ],
];

//@formatter:on
