<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epc_unicaen_renderer_template_id_fk_2',
    'table'       => 'entretienprofessionnel_campagne',
    'rtable'      => 'unicaen_renderer_template',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'unicaen_content_content_pk',
    'columns'     => [
        'template_cref_id' => 'id',
    ],
];

//@formatter:on
