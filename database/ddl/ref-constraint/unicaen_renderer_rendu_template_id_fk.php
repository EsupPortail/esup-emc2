<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_renderer_rendu_template_id_fk',
    'table'       => 'unicaen_renderer_rendu',
    'rtable'      => 'unicaen_renderer_template',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'unicaen_content_content_id_uindex',
    'columns'     => [
        'template_id' => 'id',
    ],
];

//@formatter:on
