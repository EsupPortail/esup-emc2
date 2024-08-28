<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_session_parametre_unicaen_utilisateur_user_null_fk_1',
    'table'       => 'formation_session_parametre',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
