<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'poste_domaine_id_fk',
    'table'       => 'poste',
    'rtable'      => 'metier_domaine',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'domaine_id_uindex',
    'columns'     => [
        'domaine' => 'id',
    ],
];

//@formatter:on
