<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'autoform_categorie_formulaire_fk',
    'table'       => 'unicaen_autoform_categorie',
    'rtable'      => 'unicaen_autoform_formulaire',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'autoform_formulaire_pk',
    'columns'     => [
        'formulaire' => 'id',
    ],
];

//@formatter:on
