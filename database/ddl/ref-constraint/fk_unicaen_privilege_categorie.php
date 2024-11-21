<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fk_unicaen_privilege_categorie',
    'table'       => 'unicaen_privilege_privilege',
    'rtable'      => 'unicaen_privilege_categorie',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_privilege_categorie_pkey',
    'columns'     => [
        'categorie_id' => 'id',
    ],
];

//@formatter:on
