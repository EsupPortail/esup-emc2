<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fdeg_formation_demande_externe_id_fk',
    'table'       => 'formation_demande_externe_gestionnaire',
    'rtable'      => 'formation_demande_externe',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_demande_externe_pk',
    'columns'     => [
        'demande_externe_id' => 'id',
    ],
];

//@formatter:on
