<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fonction_activite_user_id_fk_2',
    'table'       => 'fonction_activite',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
