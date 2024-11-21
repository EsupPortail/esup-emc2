<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_domaine_famille',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'domaine_id' => [
            'name'        => 'domaine_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'famille_id' => [
            'name'        => 'famille_id',
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
