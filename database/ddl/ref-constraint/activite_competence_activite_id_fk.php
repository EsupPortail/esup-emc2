<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'activite_competence_activite_id_fk',
    'table'       => 'activite_competence',
    'rtable'      => 'activite',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'activite_id_uindex',
    'columns'     => [
        'activite_id' => 'id',
    ],
];

//@formatter:on
