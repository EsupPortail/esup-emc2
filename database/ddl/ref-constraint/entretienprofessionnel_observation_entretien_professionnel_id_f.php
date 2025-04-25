<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_observation_entretien_professionnel_id_f',
    'table'       => 'entretienprofessionnel_observation_old',
    'rtable'      => 'entretienprofessionnel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'entretien_professionnel_id_uindex',
    'columns'     => [
        'entretien_id' => 'id',
    ],
];

//@formatter:on
