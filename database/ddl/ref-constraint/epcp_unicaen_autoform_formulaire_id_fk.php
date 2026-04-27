<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epcp_unicaen_autoform_formulaire_id_fk',
    'table'       => 'entretienprofessionnel_campagne_configuration_presaisie',
    'rtable'      => 'unicaen_autoform_formulaire',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'autoform_formulaire_pk',
    'columns'     => [
        'formulaire_id' => 'id',
    ],
];

//@formatter:on
