<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'activite_domaine_activite_id_fk',
    'table'       => 'activite_domaine',
    'rtable'      => 'activite',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'activite_id_uindex',
    'columns'     => [
        'activite_id' => 'id',
    ],
];

//@formatter:on
