<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fonction_destination_user_id_fk_1',
    'table'       => 'fonction_destination',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
