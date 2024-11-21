<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_parametre_parametre_unicaen_parametre_categorie_id_fk',
    'table'       => 'unicaen_parametre_parametre',
    'rtable'      => 'unicaen_parametre_categorie',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_parametre_categorie_pk',
    'columns'     => [
        'categorie_id' => 'id',
    ],
];

//@formatter:on
