<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'element_competence_element_competence_referentiel_id_fk',
    'table'       => 'element_competence',
    'rtable'      => 'element_competence_referentiel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'element_competence_referentiel_pk',
    'columns'     => [
        'referentiel_id' => 'id',
    ],
];

//@formatter:on
