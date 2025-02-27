<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fiche_metier_formation_formation_id_fk',
    'table'       => 'fichemetier_formation',
    'rtable'      => 'formation',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_pk',
    'columns'     => [
        'formation_id' => 'id',
    ],
];

//@formatter:on
