<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_agent_force_unicaen_utilisateur_user_id_fk',
    'table'       => 'structure_agent_force',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
