<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formaton_formation_domaine_formation_domaine_id_fk',
    'table'       => 'formation_formation_domaine',
    'rtable'      => 'formation_domaine',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_domaine_pk',
    'columns'     => [
        'domaine_id' => 'id',
    ],
];

//@formatter:on
