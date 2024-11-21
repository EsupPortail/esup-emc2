<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'autoform_champ_destructeur_fk',
    'table'       => 'unicaen_autoform_champ',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
