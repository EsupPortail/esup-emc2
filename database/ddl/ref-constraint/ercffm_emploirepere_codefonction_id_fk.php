<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ercffm_emploirepere_codefonction_id_fk',
    'table'       => 'emploirepere_emploirepere_codefonction_fichemetier',
    'rtable'      => 'emploirepere_codefonction',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'emploirepere_codefonction_pk',
    'columns'     => [
        'codefonction_id' => 'id',
    ],
];

//@formatter:on
