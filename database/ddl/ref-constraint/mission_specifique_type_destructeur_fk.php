<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'mission_specifique_type_destructeur_fk',
    'table'       => 'mission_specifique_type',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
