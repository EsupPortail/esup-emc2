<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'mission_specifique_modificateur_fk',
    'table'       => 'mission_specifique',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
