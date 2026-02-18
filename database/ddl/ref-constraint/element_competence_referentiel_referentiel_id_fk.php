<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'element_competence_referentiel_referentiel_id_fk',
    'table'       => 'element_competence',
    'rtable'      => 'referentiel_referentiel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'referentiel_referentiel_pk',
    'columns'     => [
        'referentiel_id' => 'id',
    ],
];

//@formatter:on
