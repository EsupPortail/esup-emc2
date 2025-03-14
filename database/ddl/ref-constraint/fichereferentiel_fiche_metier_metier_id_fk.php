<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichereferentiel_fiche_metier_metier_id_fk',
    'table'       => 'fichereferentiel_fiche',
    'rtable'      => 'metier_metier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET DEFAULT',
    'index'       => 'metier_pkey',
    'columns'     => [
        'metier_id' => 'id',
    ],
];

//@formatter:on
