<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_etat_fichemetier_id_fk',
    'table'       => 'fichemetier_etat',
    'rtable'      => 'fichemetier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_type_metier_pkey',
    'columns'     => [
        'fichemetier_id' => 'id',
    ],
];

//@formatter:on
