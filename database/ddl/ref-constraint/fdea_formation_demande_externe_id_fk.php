<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fdea_formation_demande_externe_id_fk',
    'table'       => 'formation_demande_externe_session',
    'rtable'      => 'formation_demande_externe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_demande_externe_pk',
    'columns'     => [
        'demande_id' => 'id',
    ],
];

//@formatter:on
