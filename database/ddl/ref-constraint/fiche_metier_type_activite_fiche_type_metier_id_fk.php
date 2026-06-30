<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fiche_metier_type_activite_fiche_type_metier_id_fk',
    'table'       => 'fichemetier_activite_old',
    'rtable'      => 'fichemetier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_type_metier_pkey',
    'columns'     => [
        'fiche_id' => 'id',
    ],
];

//@formatter:on
