<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'competence_type_createur_fk',
    'table'       => 'element_competence_type',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
