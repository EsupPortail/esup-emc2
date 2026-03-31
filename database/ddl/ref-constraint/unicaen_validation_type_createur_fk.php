<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_validation_type_createur_fk',
    'table'       => 'unicaen_validation_type',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
