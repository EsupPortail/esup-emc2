<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fmc_application_defaut_unicaen_utilisateur_user_id_fk',
    'table'       => 'fichemetier_configuration_application_defaut',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
