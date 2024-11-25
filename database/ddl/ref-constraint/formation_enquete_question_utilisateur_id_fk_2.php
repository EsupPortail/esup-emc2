<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_enquete_question_utilisateur_id_fk_2',
    'table'       => 'unicaen_enquete_question',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on