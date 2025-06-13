<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_enquete_categorie_utilisateur_id_fk_1',
    'table'       => 'unicaen_enquete_groupe',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_createur_id' => 'id',
    ],
];

//@formatter:on
