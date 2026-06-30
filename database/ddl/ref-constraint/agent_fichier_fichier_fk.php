<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_fichier_fichier_fk',
    'table'       => 'agent_fichier',
    'rtable'      => 'unicaen_fichier_fichier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fichier_fichier_pk',
    'columns'     => [
        'fichier' => 'id',
    ],
];

//@formatter:on
