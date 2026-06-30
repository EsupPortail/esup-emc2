<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'mission_specifique_mission_specifique_type_id_fk',
    'table'       => 'mission_specifique',
    'rtable'      => 'mission_specifique_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'mission_specifique_type_pk',
    'columns'     => [
        'type_id' => 'id',
    ],
];

//@formatter:on
