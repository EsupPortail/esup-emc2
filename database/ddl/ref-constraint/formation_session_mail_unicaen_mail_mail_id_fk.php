<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_session_mail_unicaen_mail_mail_id_fk',
    'table'       => 'formation_session_mail',
    'rtable'      => 'unicaen_mail_mail',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'umail_pkey',
    'columns'     => [
        'mail_id' => 'id',
    ],
];

//@formatter:on
