<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'autoform_champ_modificateur_fk',
    'table'       => 'unicaen_autoform_champ',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
