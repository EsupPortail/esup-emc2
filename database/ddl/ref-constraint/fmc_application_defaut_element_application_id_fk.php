<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fmc_application_defaut_element_application_id_fk',
    'table'       => 'fichemetier_configuration_application_defaut',
    'rtable'      => 'element_application',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'application_informations_pkey',
    'columns'     => [
        'application_id' => 'id',
    ],
];

//@formatter:on
