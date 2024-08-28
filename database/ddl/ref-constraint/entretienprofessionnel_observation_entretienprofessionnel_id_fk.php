<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_observation_entretienprofessionnel_id_fk',
    'table'       => 'entretienprofessionnel_observation',
    'rtable'      => 'entretienprofessionnel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'entretien_professionnel_pk',
    'columns'     => [
        'entretien_id' => 'id',
    ],
];

//@formatter:on
