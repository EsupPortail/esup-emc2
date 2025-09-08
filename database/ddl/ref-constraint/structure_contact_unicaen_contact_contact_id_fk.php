<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_contact_unicaen_contact_contact_id_fk',
    'table'       => 'structure_contact',
    'rtable'      => 'unicaen_contact_contact',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'unicaen_contact_contact_pk',
    'columns'     => [
        'contact_id' => 'id',
    ],
];

//@formatter:on
