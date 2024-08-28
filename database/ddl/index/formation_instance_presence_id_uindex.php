<?php

//@formatter:off

return [
    'name'    => 'formation_instance_presence_id_uindex',
    'unique'  => TRUE,
    'type'    => 'btree',
    'table'   => 'formation_presence',
    'schema'  => 'public',
    'columns' => [
        'id',
    ],
];

//@formatter:on
