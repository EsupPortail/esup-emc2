<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_evenement_entretienprofessionnel_id_fk',
    'table'       => 'entretienprofessionnel_evenement',
    'rtable'      => 'entretienprofessionnel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'entretien_professionnel_id_uindex',
    'columns'     => [
        'entretien_id' => 'id',
    ],
];

//@formatter:on
