<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_groupe_destructeur_fk',
    'table'       => 'formation_groupe',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
