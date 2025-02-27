<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance_frais_user_id_fk_2',
    'table'       => 'formation_instance_frais',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
