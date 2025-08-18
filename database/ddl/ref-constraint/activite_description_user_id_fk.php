<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'activite_description_user_id_fk',
    'table'       => 'missionprincipale_activite',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
