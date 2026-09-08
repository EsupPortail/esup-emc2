<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'missionprincipale_application_missionprincipale_id_fk',
    'table'       => 'missionprincipale_application',
    'rtable'      => 'missionprincipale',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'missionprincipale_pk',
    'columns'     => [
        'mission_id' => 'id',
    ],
];

//@formatter:on
