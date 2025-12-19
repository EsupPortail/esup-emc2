<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epc_indicateur_unicaen_indicateur_indicateur_id_fk',
    'table'       => 'entretienprofessionnel_campagne_indicateur',
    'rtable'      => 'unicaen_indicateur_indicateur',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'indicateur_pk',
    'columns'     => [
        'indicateur_id' => 'id',
    ],
];

//@formatter:on
