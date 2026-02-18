<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'missionprincipale_referentiel_referentiel_id_fk',
    'table'       => 'missionprincipale_old',
    'rtable'      => 'referentiel_referentiel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'referentiel_referentiel_pk',
    'columns'     => [
        'referentiel_id' => 'id',
    ],
];

//@formatter:on
