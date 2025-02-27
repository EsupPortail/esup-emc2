<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_enquete_question_formation_enquete_categorie_id_fk',
    'table'       => 'unicaen_enquete_question',
    'rtable'      => 'unicaen_enquete_groupe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'formation_enquete_categorie_pkey',
    'columns'     => [
        'groupe_id' => 'id',
    ],
];

//@formatter:on
