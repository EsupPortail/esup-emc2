<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'emploirepere_codefonction_carriere_niveau_fonction_id_fk',
    'table'       => 'emploirepere_codefonction',
    'rtable'      => 'carriere_niveau_fonction',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'carriere_niveau_fonction_pk',
    'columns'     => [
        'niveau_fonction_id' => 'id',
    ],
];

//@formatter:on
