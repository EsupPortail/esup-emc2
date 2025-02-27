<?php

//@formatter:off

return [
    'schema'  => 'public',
    'name'    => 'formation_formateur_session_pk',
    'table'   => 'formation_formateur_session',
    'index'   => 'formation_formateur_session_pk',
    'columns' => [
        'formateur_id',
        'session_id',
    ],
];

//@formatter:on
