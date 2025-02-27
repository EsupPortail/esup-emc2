<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fiche_metier_formation_fiche_metier_id_fk',
    'table'       => 'fichemetier_formation',
    'rtable'      => 'fichemetier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_type_metier_pkey',
    'columns'     => [
        'fiche_metier_id' => 'id',
    ],
];

//@formatter:on
