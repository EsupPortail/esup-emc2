<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_domaine_famille_metier_domaine_id_fk',
    'table'       => 'metier_domaine_famille',
    'rtable'      => 'metier_domaine',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'domaine_id_uindex',
    'columns'     => [
        'domaine_id' => 'id',
    ],
];

//@formatter:on
