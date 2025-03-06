<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_accompagnement_unicaen_utilisateur_user_id_fk',
    'table'       => 'agent_ccc_accompagnement',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
