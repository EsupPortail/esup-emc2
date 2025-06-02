<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_observateur_entretienprofessionnel_id_fk',
    'table'       => 'entretienprofessionnel_observateur',
    'rtable'      => 'entretienprofessionnel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'entretien_professionnel_pk',
    'columns'     => [
        'entretien_id' => 'id',
    ],
];

//@formatter:on
