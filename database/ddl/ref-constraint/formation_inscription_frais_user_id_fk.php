<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_inscription_frais_user_id_fk',
    'table'       => 'formation_inscription_frais',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
