<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'competence_theme_modificateur_fk',
    'table'       => 'element_competence_theme',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
