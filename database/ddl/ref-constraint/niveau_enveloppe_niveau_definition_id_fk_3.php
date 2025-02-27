<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'niveau_enveloppe_niveau_definition_id_fk_3',
    'table'       => 'carriere_niveau_enveloppe',
    'rtable'      => 'carriere_niveau',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'niveau_definition_pk',
    'columns'     => [
        'valeur_recommandee_id' => 'id',
    ],
];

//@formatter:on
