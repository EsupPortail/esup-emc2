<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'autoform_formulaire_instance_autoform_formulaire_id_fk',
    'table'       => 'unicaen_autoform_formulaire_instance',
    'rtable'      => 'unicaen_autoform_formulaire',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'autoform_formulaire_id_uindex',
    'columns'     => [
        'formulaire' => 'id',
    ],
];

//@formatter:on
