<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'autoform_formulaire_instance_destructeur_fk',
    'table'       => 'unicaen_autoform_formulaire_instance',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
