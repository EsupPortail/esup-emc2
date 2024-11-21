<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fk_evenement_instance_etat',
    'table'       => 'unicaen_evenement_instance',
    'rtable'      => 'unicaen_evenement_etat',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'pk_evenement_etat',
    'columns'     => [
        'etat_id' => 'id',
    ],
];

//@formatter:on
