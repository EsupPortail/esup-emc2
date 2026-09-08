<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fmc_competence_defaut_element_competence_id_fk',
    'table'       => 'fichemetier_configuration_competence_defaut',
    'rtable'      => 'element_competence',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'competence_pk',
    'columns'     => [
        'competence_id' => 'id',
    ],
];

//@formatter:on
