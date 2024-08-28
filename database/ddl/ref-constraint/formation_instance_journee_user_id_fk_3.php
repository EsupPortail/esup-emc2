<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance_journee_user_id_fk_3',
    'table'       => 'formation_seance',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
