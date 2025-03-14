<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'configuration_fichemetier_createur_fk',
    'table'       => 'configuration_fichemetier',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
