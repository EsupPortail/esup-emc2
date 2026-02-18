<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fiche_metier_type_activite_activite_id_fk',
    'table'       => 'fichemetier_activite_old',
    'rtable'      => 'activite_old',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'activite_pkey',
    'columns'     => [
        'activite_id' => 'id',
    ],
];

//@formatter:on
