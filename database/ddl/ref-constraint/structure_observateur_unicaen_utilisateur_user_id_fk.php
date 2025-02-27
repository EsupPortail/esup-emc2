<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_observateur_unicaen_utilisateur_user_id_fk',
    'table'       => 'structure_observateur',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'user_pkey',
    'columns'     => [
        'utilisateur_id' => 'id',
    ],
];

//@formatter:on
