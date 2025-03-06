<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'niveau_enveloppe_niveau_definition_id_fk',
    'table'       => 'carriere_niveau_enveloppe',
    'rtable'      => 'carriere_niveau',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'niveau_definition_id_uindex',
    'columns'     => [
        'borne_inferieure_id' => 'id',
    ],
];

//@formatter:on
