<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_etat_instance_histo_modificateur_id_fkey',
    'table'       => 'unicaen_etat_instance',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
