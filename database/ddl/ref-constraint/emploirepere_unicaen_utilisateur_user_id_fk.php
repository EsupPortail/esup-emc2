<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'emploirepere_unicaen_utilisateur_user_id_fk',
    'table'       => 'emploirepere',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET DEFAULT',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
