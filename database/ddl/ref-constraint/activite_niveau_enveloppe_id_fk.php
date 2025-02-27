<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'activite_niveau_enveloppe_id_fk',
    'table'       => 'activite',
    'rtable'      => 'carriere_niveau_enveloppe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'niveau_enveloppe_pk',
    'columns'     => [
        'niveaux_id' => 'id',
    ],
];

//@formatter:on
