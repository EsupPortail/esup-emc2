<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epc_unicaen_autoform_formulaire_id_fk',
    'table'       => 'entretienprofessionnel_campagne',
    'rtable'      => 'unicaen_autoform_formulaire',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'autoform_formulaire_pk',
    'columns'     => [
        'formulaire_crep_id' => 'id',
    ],
];

//@formatter:on
