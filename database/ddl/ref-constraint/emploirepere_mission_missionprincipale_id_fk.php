<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'emploirepere_mission_missionprincipale_id_fk',
    'table'       => 'emploirepere_mission',
    'rtable'      => 'missionprincipale',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'missionprincipale_pk',
    'columns'     => [
        'mission_id' => 'id',
    ],
];

//@formatter:on
