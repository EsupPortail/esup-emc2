<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_validation_instance_createur_fk',
    'table'       => 'unicaen_validation_instance',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
