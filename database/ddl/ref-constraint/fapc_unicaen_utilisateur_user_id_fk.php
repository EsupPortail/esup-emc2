<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fapc_unicaen_utilisateur_user_id_fk',
    'table'       => 'formation_action_cout_previsionnel',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
