<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_accompagnement_unicaen_utilisateur_user_id_fk_2',
    'table'       => 'agent_ccc_accompagnement',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
