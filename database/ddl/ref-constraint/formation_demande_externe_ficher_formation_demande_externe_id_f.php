<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_demande_externe_ficher_formation_demande_externe_id_f',
    'table'       => 'formation_demande_externe_fichier',
    'rtable'      => 'formation_demande_externe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_demande_externe_pk',
    'columns'     => [
        'demande_id' => 'id',
    ],
];

//@formatter:on
