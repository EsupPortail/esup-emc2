<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_obtenue_competence_formation_id_fk',
    'table'       => 'formation_obtenue_competence',
    'rtable'      => 'formation',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_pk',
    'columns'     => [
        'formation_id' => 'id',
    ],
];

//@formatter:on
