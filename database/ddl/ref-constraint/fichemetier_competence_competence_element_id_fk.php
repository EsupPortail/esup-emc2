<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_competence_competence_element_id_fk',
    'table'       => 'fichemetier_competence',
    'rtable'      => 'element_competence_element',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'competence_element_pk',
    'columns'     => [
        'competence_element_id' => 'id',
    ],
];

//@formatter:on
