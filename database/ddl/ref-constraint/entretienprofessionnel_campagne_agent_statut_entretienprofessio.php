<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel_campagne_agent_statut_entretienprofessio',
    'table'       => 'entretienprofessionnel_campagne_agent_statut',
    'rtable'      => 'entretienprofessionnel',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'entretien_professionnel_pk',
    'columns'     => [
        'entretienprofessionnel_id' => 'id',
    ],
];

//@formatter:on
