<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_etat_entretien_id_fk',
    'table'       => 'entretienprofessionnel_etat',
    'rtable'      => 'entretienprofessionnel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'entretien_professionnel_pk',
    'columns'     => [
        'entretien_id' => 'id',
    ],
];

//@formatter:on
