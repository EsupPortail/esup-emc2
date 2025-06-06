<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_element_application',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'agent_id'               => [
            'name'        => 'agent_id',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 40,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'application_element_id' => [
            'name'        => 'application_element_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
