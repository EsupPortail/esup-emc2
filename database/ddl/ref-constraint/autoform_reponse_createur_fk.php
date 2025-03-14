<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'autoform_reponse_createur_fk',
    'table'       => 'unicaen_autoform_formulaire_reponse',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
