<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'missionprincipale_unicaen_utilisateur_user_id_fk2',
    'table'       => 'missionprincipale',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
