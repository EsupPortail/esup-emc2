<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_domaine_unicaen_utilisateur_user_id_fk2',
    'table'       => 'formation_domaine',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
