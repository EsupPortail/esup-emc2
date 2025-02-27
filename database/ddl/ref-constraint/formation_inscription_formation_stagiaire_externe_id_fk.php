<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_inscription_formation_stagiaire_externe_id_fk',
    'table'       => 'formation_inscription',
    'rtable'      => 'formation_stagiaire_externe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'formation_stagiaire_externe_pk',
    'columns'     => [
        'stagiaire_id' => 'id',
    ],
];

//@formatter:on
