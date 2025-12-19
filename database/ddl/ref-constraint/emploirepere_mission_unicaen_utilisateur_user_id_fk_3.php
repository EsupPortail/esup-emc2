<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'emploirepere_mission_unicaen_utilisateur_user_id_fk_3',
    'table'       => 'emploirepere_mission',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
