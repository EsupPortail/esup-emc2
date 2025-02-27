<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_stageobs_unicaen_utilisateur_user_id_fk_2',
    'table'       => 'agent_ccc_stageobs',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
