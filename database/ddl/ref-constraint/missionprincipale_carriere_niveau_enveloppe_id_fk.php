<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'missionprincipale_carriere_niveau_enveloppe_id_fk',
    'table'       => 'missionprincipale',
    'rtable'      => 'carriere_niveau_enveloppe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'niveau_enveloppe_id_uindex',
    'columns'     => [
        'niveau_id' => 'id',
    ],
];

//@formatter:on
