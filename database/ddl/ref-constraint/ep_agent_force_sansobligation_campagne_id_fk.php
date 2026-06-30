<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ep_agent_force_sansobligation_campagne_id_fk',
    'table'       => 'entretienprofessionnel_agent_force',
    'rtable'      => 'entretienprofessionnel_campagne',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'entretienprofessionnel_campagne_pk',
    'columns'     => [
        'campagne_id' => 'id',
    ],
];

//@formatter:on
