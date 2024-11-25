<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_reference_user_id_fk_2',
    'table'       => 'metier_reference',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on