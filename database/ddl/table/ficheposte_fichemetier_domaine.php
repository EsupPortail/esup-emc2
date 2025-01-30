<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_fichemetier_domaine',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'ficheposte_fichemetier_domaine_id_seq',
    'columns'     => [
        'domaine_id'            => [
            'name'        => 'domaine_id',
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
        'fichemetierexterne_id' => [
            'name'        => 'fichemetierexterne_id',
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
        'id'                    => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'ficheposte_fichemetier_domaine_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'quotite'               => [
            'name'        => 'quotite',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => '100',
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
