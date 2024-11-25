<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichereferentiel_fiche_metier_referentiel_id_fk',
    'table'       => 'fichereferentiel_fiche',
    'rtable'      => 'metier_referentiel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'metier_referentiel_id_uindex',
    'columns'     => [
        'referentiel_id' => 'id',
    ],
];

//@formatter:on
