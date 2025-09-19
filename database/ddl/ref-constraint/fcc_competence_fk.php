<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fcc_competence_fk',
    'table'       => 'ficheposte_competence_retiree',
    'rtable'      => 'element_competence',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'competence_pk',
    'columns'     => [
        'competence_id' => 'id',
    ],
];

//@formatter:on
