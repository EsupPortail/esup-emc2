<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_stagiaire_externe_unicaen_utilisateur_user_id_fk4',
    'table'       => 'formation_stagiaire_externe',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'utilisateur_id' => 'id',
    ],
];

//@formatter:on
