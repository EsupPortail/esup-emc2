<?php

//@formatter:off

return [
    'name'    => 'unicaen_glossaire_definition_terme_uindex',
    'unique'  => TRUE,
    'type'    => 'btree',
    'table'   => 'unicaen_aide_glossaire_definition',
    'schema'  => 'public',
    'columns' => [
        'terme',
    ],
];

//@formatter:on
