<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance_journee_user_id_fk_2',
    'table'       => 'formation_seance',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
