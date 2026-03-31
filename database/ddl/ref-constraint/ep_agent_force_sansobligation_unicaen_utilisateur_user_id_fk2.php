<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ep_agent_force_sansobligation_unicaen_utilisateur_user_id_fk2',
    'table'       => 'entretienprofessionnel_agent_force',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
