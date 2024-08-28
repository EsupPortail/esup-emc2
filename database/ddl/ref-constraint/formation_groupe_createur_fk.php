<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_groupe_createur_fk',
    'table'       => 'formation_groupe',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
