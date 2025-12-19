<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'element_competence_synonyme_element_competence_id_fk',
    'table'       => 'element_competence_synonyme',
    'rtable'      => 'element_competence',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'competence_pk',
    'columns'     => [
        'competence_id' => 'id',
    ],
];

//@formatter:on
