<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epccp_unicaen_utilisateur_user_id_fk',
    'table'       => 'entretienprofessionnel_campagne_configuration_presaisie',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
