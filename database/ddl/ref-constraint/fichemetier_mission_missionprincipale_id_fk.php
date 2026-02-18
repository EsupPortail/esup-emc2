<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_mission_missionprincipale_id_fk',
    'table'       => 'fichemetier_mission_old',
    'rtable'      => 'missionprincipale_old',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'missionprincipale_old_pk',
    'columns'     => [
        'mission_id' => 'id',
    ],
];

//@formatter:on
