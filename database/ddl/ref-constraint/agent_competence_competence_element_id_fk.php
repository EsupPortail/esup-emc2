<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_competence_competence_element_id_fk',
    'table'       => 'agent_element_competence',
    'rtable'      => 'element_competence_element',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'competence_element_id_uindex',
    'columns'     => [
        'competence_element_id' => 'id',
    ],
];

//@formatter:on
