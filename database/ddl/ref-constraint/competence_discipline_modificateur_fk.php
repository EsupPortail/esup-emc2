<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'competence_discipline_modificateur_fk',
    'table'       => 'element_competence_discipline',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
