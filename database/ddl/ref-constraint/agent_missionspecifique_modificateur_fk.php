<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_missionspecifique_modificateur_fk',
    'table'       => 'agent_missionspecifique',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
