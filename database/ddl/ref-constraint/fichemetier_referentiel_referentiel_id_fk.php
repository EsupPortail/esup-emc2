<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_referentiel_referentiel_id_fk',
    'table'       => 'fichemetier',
    'rtable'      => 'referentiel_referentiel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'referentiel_referentiel_pk',
    'columns'     => [
        'referentiel_id' => 'id',
    ],
];

//@formatter:on
