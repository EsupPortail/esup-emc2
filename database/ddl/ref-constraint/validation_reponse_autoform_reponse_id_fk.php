<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'validation_reponse_autoform_reponse_id_fk',
    'table'       => 'unicaen_autoform_validation_reponse',
    'rtable'      => 'unicaen_autoform_formulaire_reponse',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'autoform_reponse_pk',
    'columns'     => [
        'reponse' => 'id',
    ],
];

//@formatter:on
