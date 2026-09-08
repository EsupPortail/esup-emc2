<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_missionspecifique_mission_specifique_id_fk',
    'table'       => 'agent_missionspecifique',
    'rtable'      => 'mission_specifique',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'mission_specifique_id_uindex',
    'columns'     => [
        'mission_id' => 'id',
    ],
];

//@formatter:on
