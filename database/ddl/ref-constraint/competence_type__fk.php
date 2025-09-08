<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'competence_type__fk',
    'table'       => 'element_competence',
    'rtable'      => 'element_competence_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'competence_type_id_uindex',
    'columns'     => [
        'type_id' => 'id',
    ],
];

//@formatter:on
