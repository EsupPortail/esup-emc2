<?php

//@formatter:off

return [
    'name'    => 'maitrise_niveau_type_niveau_uindex',
    'unique'  => TRUE,
    'type'    => 'btree',
    'table'   => 'element_niveau',
    'schema'  => 'public',
    'columns' => [
        'type',
        'niveau',
    ],
];

//@formatter:on
