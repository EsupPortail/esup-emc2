<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichereferentiel_competence_fichereferentiel_fiche_id_fk',
    'table'       => 'fichereferentiel_competence',
    'rtable'      => 'fichereferentiel_fiche',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fichereferentiel_fiche_pk',
    'columns'     => [
        'fiche_referentiel_id' => 'id',
    ],
];

//@formatter:on
