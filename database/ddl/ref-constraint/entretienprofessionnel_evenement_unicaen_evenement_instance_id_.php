<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_evenement_unicaen_evenement_instance_id_',
    'table'       => 'entretienprofessionnel_evenement',
    'rtable'      => 'unicaen_evenement_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'pk_evenement_instance',
    'columns'     => [
        'evenement_id' => 'id',
    ],
];

//@formatter:on
