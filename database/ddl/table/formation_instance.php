<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'formation_instance_id_seq',
    'columns'     => [
        'affichage'                => [
            'name'        => 'affichage',
            'type'        => 'bool',
            'bdd-type'    => 'boolean',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'true',
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'auto_inscription'         => [
            'name'        => 'auto_inscription',
            'type'        => 'bool',
            'bdd-type'    => 'boolean',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'false',
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'complement'               => [
            'name'        => 'complement',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'cout_ht'                  => [
            'name'        => 'cout_ht',
            'type'        => 'float',
            'bdd-type'    => 'double precision',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'cout_ttc'                 => [
            'name'        => 'cout_ttc',
            'type'        => 'float',
            'bdd-type'    => 'double precision',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 18,
            'commentaire' => NULL,
        ],
        'cout_vacation'            => [
            'name'        => 'cout_vacation',
            'type'        => 'float',
            'bdd-type'    => 'double precision',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 21,
            'commentaire' => NULL,
        ],
        'date_cloture_inscription' => [
            'name'        => 'date_cloture_inscription',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 23,
            'commentaire' => NULL,
        ],
        'formation_id'             => [
            'name'        => 'formation_id',
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
        'histo_createur_id'        => [
            'name'        => 'histo_createur_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'histo_creation'           => [
            'name'        => 'histo_creation',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'histo_destructeur_id'     => [
            'name'        => 'histo_destructeur_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'histo_destruction'        => [
            'name'        => 'histo_destruction',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'histo_modificateur_id'    => [
            'name'        => 'histo_modificateur_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'histo_modification'       => [
            'name'        => 'histo_modification',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'id'                       => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'formation_instance_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'id_source'                => [
            'name'        => 'id_source',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'lieu'                     => [
            'name'        => 'lieu',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'nb_place_complementaire'  => [
            'name'        => 'nb_place_complementaire',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'nb_place_principale'      => [
            'name'        => 'nb_place_principale',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'parametre_id'             => [
            'name'        => 'parametre_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'recette_ttc'              => [
            'name'        => 'recette_ttc',
            'type'        => 'float',
            'bdd-type'    => 'double precision',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 22,
            'commentaire' => NULL,
        ],
        'source_id'                => [
            'name'        => 'source_id',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 128,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'type'                     => [
            'name'        => 'type',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 13,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
