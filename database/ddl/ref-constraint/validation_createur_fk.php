<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'validation_createur_fk',
    'table'       => 'unicaen_autoform_validation',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
