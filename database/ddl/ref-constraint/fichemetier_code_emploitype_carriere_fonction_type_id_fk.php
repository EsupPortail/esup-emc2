<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_code_emploitype_carriere_fonction_type_id_fk',
    'table'       => 'fichemetier_code_emploitype',
    'rtable'      => 'carriere_niveau_fonction',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'carriere_niveau_fonction_pk',
    'columns'     => [
        'fonction_code' => 'id',
    ],
];

//@formatter:on
