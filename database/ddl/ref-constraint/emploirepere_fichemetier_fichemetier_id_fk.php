<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'emploirepere_fichemetier_fichemetier_id_fk',
    'table'       => 'emploirepere_fichemetier',
    'rtable'      => 'fichemetier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'fiche_type_metier_pkey',
    'columns'     => [
        'fichemetier_id' => 'id',
    ],
];

//@formatter:on
