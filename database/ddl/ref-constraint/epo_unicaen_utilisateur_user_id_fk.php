<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epo_unicaen_utilisateur_user_id_fk',
    'table'       => 'entretienprofessionnel_observateur',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'user_id' => 'id',
    ],
];

//@formatter:on
