<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fadr_description_fk',
    'table'       => 'ficheposte_activitedescription_retiree',
    'rtable'      => 'missionprincipale_activite',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'activite_description_id_uindex',
    'columns'     => [
        'description_id' => 'id',
    ],
];

//@formatter:on
