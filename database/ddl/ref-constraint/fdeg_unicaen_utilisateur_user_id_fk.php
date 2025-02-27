<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fdeg_unicaen_utilisateur_user_id_fk',
    'table'       => 'formation_demande_externe_gestionnaire',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'user_pkey',
    'columns'     => [
        'gestionnaire_id' => 'id',
    ],
];

//@formatter:on
