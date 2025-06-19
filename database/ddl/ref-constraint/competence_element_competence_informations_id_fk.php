<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'competence_element_competence_informations_id_fk',
    'table'       => 'element_competence_element',
    'rtable'      => 'element_competence',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'competence_id_uindex',
    'columns'     => [
        'competence_id' => 'id',
    ],
];

//@formatter:on
