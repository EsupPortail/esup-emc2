<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_domaine_unicaen_utilisateur_user_id_fk',
    'table'       => 'formation_domaine',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
