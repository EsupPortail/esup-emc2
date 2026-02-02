<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_mission_mission_element_id_fk',
    'table'       => 'fichemetier_mission',
    'rtable'      => 'missionprincipale_element',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'missionprincipale_element_pk',
    'columns'     => [
        'mission_element_id' => 'id',
    ],
];

//@formatter:on
