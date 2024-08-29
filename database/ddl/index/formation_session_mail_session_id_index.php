<?php

//@formatter:off

return [
    'name'    => 'formation_session_mail_session_id_index',
    'unique'  => FALSE,
    'type'    => 'btree',
    'table'   => 'formation_session_mail',
    'schema'  => 'public',
    'columns' => [
        'session_id',
    ],
];

//@formatter:on
