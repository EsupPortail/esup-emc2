<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_plan_formation_histo_modificateur_id_fkey',
    'table'       => 'formation_plan_formation',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
