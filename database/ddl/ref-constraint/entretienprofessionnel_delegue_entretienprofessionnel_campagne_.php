<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_delegue_entretienprofessionnel_campagne_',
    'table'       => 'entretienprofessionnel_delegue',
    'rtable'      => 'entretienprofessionnel_campagne',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'entretienprofessionnel_campagne_pk',
    'columns'     => [
        'campagne_id' => 'id',
    ],
];

//@formatter:on
