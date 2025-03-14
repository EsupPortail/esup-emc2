<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_formateur_session_formation_formateur_id_fk',
    'table'       => 'formation_formateur_session',
    'rtable'      => 'formation_formateur',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_instance_formateur_pk',
    'columns'     => [
        'formateur_id' => 'id',
    ],
];

//@formatter:on
