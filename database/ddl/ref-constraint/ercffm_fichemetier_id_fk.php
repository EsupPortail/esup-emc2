<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ercffm_fichemetier_id_fk',
    'table'       => 'emploirepere_emploirepere_codefonction_fichemetier',
    'rtable'      => 'fichemetier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_type_metier_pkey',
    'columns'     => [
        'fichemetier_id' => 'id',
    ],
];

//@formatter:on
