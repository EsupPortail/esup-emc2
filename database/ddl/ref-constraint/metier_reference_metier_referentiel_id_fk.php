<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_reference_metier_referentiel_id_fk',
    'table'       => 'metier_reference',
    'rtable'      => 'metier_referentiel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'metier_referentiel_pk',
    'columns'     => [
        'referentiel_id' => 'id',
    ],
];

//@formatter:on
