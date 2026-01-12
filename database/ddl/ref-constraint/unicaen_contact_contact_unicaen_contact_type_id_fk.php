<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_contact_contact_unicaen_contact_type_id_fk',
    'table'       => 'unicaen_contact_contact',
    'rtable'      => 'unicaen_contact_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_contact_type_pk',
    'columns'     => [
        'type_id' => 'id',
    ],
];

//@formatter:on
