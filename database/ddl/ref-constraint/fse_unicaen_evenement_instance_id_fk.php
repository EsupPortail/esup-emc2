<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fse_unicaen_evenement_instance_id_fk',
    'table'       => 'formation_session_evenement',
    'rtable'      => 'unicaen_evenement_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'pk_evenement_instance',
    'columns'     => [
        'evenement_instance_id' => 'id',
    ],
];

//@formatter:on
