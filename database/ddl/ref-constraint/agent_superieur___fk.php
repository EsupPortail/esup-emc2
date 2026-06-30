<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_superieur___fk',
    'table'       => 'agent_hierarchie_superieur',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
