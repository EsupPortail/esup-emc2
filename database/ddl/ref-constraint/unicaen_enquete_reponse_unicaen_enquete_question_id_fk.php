<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_enquete_reponse_unicaen_enquete_question_id_fk',
    'table'       => 'unicaen_enquete_reponse',
    'rtable'      => 'unicaen_enquete_question',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_enquete_question_id_uindex',
    'columns'     => [
        'question_id' => 'id',
    ],
];

//@formatter:on
