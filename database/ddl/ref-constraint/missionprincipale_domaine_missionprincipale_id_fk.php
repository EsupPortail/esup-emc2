<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'missionprincipale_domaine_missionprincipale_id_fk',
    'table'       => 'missionprincipale_domaine',
    'rtable'      => 'missionprincipale',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'missionprincipale_pk',
    'columns'     => [
        'mission_id' => 'id',
    ],
];

//@formatter:on
