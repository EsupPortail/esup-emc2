<?php

//@formatter:off

return [
    'name'    => 'fse_session_id_evenement_instance_id_index',
    'unique'  => FALSE,
    'type'    => 'btree',
    'table'   => 'formation_session_evenement',
    'schema'  => 'public',
    'columns' => [
        'session_id',
        'evenement_instance_id',
    ],
];

//@formatter:on
