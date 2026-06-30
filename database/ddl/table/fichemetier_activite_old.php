<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_activite_old',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'activite_id' => [
            'name'        => 'activite_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'fiche_id'    => [
            'name'        => 'fiche_id',
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
        'id'          => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'fichemetier_activite_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'position'    => [
            'name'        => 'position',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
