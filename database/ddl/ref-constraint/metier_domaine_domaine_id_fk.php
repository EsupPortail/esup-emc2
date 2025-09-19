<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_domaine_domaine_id_fk',
    'table'       => 'metier_metier_domaine',
    'rtable'      => 'metier_domaine',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'domaine_pk',
    'columns'     => [
        'domaine_id' => 'id',
    ],
];

//@formatter:on
