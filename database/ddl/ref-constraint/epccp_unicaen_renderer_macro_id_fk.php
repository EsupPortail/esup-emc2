<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epccp_unicaen_renderer_macro_id_fk',
    'table'       => 'entretienprofessionnel_campagne_configuration_presaisie',
    'rtable'      => 'unicaen_renderer_macro',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_document_macro_pk',
    'columns'     => [
        'macro_id' => 'id',
    ],
];

//@formatter:on
