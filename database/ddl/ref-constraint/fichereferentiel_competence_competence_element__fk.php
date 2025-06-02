<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichereferentiel_competence_competence_element__fk',
    'table'       => 'fichereferentiel_competence',
    'rtable'      => 'element_competence_element',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'competence_element_pk',
    'columns'     => [
        'competence_element_id' => 'id',
    ],
];

//@formatter:on
