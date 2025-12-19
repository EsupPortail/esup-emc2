<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'element_competence_element_competence_discipline_id_fk',
    'table'       => 'element_competence',
    'rtable'      => 'element_competence_discipline',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'competence_discipline_pk',
    'columns'     => [
        'discipline_id' => 'id',
    ],
];

//@formatter:on
