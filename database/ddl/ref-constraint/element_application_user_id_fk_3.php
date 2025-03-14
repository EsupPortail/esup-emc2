<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'element_application_user_id_fk_3',
    'table'       => 'element_application',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
