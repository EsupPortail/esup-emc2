<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'activite_application_application_element_id_fk',
    'table'       => 'missionprincipale_application',
    'rtable'      => 'element_application_element',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'application_element_pk',
    'columns'     => [
        'application_element_id' => 'id',
    ],
];

//@formatter:on
