<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'uoi_unicaen_utilisateur_user_id_fk_1',
    'table'       => 'unicaen_observation_observation_instance',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
