<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'element_application_user_id_fk',
    'table'       => 'element_application',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
