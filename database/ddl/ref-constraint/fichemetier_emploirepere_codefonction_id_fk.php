<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_emploirepere_codefonction_id_fk',
    'table'       => 'fichemetier',
    'rtable'      => 'emploirepere_codefonction',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'emploirepere_codefonction_pk',
    'columns'     => [
        'code_fonction_id' => 'id',
    ],
];

//@formatter:on
