<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ercffm_emploirepere_emploirepere_id_fk',
    'table'       => 'emploirepere_emploirepere_codefonction_fichemetier',
    'rtable'      => 'emploirepere_emploirepere',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'emploirepere_emploirepere_pk',
    'columns'     => [
        'emploirepere_id' => 'id',
    ],
];

//@formatter:on
