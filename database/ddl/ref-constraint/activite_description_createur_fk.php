<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'activite_description_createur_fk',
    'table'       => 'missionprincipale_activite',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
