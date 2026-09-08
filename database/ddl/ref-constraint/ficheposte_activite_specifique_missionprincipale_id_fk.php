<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_activite_specifique_missionprincipale_id_fk',
    'table'       => 'ficheposte_missionsadditionnelles',
    'rtable'      => 'missionprincipale',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'missionprincipale_pk',
    'columns'     => [
        'mission_id' => 'id',
    ],
];

//@formatter:on
