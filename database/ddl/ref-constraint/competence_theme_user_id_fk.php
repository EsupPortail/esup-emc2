<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'competence_theme_user_id_fk',
    'table'       => 'element_competence_theme',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
