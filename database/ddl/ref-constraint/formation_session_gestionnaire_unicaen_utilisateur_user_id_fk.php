<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_session_gestionnaire_unicaen_utilisateur_user_id_fk',
    'table'       => 'formation_session_gestionnaire',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'gestionnaire_id' => 'id',
    ],
];

//@formatter:on
