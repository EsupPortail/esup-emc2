<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretien_professionnel_campagne_id_fk',
    'table'       => 'entretienprofessionnel',
    'rtable'      => 'entretienprofessionnel_campagne',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'entretienprofessionnel_campagne_id_uindex',
    'columns'     => [
        'campagne_id' => 'id',
    ],
];

//@formatter:on
