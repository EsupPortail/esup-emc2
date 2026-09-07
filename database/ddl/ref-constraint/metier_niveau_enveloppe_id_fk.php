<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_niveau_enveloppe_id_fk',
    'table'       => 'metier_metier',
    'rtable'      => 'carriere_niveau_enveloppe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'niveau_enveloppe_id_uindex',
    'columns'     => [
        'niveaux_id' => 'id',
    ],
];

//@formatter:on
