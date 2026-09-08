<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'missionprincipale_activite_missionprincipale_id_fk',
    'table'       => 'missionprincipale_activite',
    'rtable'      => 'missionprincipale',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'missionprincipale_pk',
    'columns'     => [
        'mission_id' => 'id',
    ],
];

//@formatter:on
