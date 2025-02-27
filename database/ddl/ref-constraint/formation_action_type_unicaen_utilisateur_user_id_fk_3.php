<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_action_type_unicaen_utilisateur_user_id_fk_3',
    'table'       => 'formation_action_type',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
