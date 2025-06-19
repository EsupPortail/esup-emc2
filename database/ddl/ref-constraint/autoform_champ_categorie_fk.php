<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'autoform_champ_categorie_fk',
    'table'       => 'unicaen_autoform_champ',
    'rtable'      => 'unicaen_autoform_categorie',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'autoform_categorie_id_uindex',
    'columns'     => [
        'categorie' => 'id',
    ],
];

//@formatter:on
