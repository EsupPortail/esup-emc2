<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'mission_specifique_mission_specifique_theme_id_fk',
    'table'       => 'mission_specifique',
    'rtable'      => 'mission_specifique_theme',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'mission_specifique_theme_pk',
    'columns'     => [
        'theme_id' => 'id',
    ],
];

//@formatter:on
