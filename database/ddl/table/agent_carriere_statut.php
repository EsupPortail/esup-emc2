<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_carriere_statut',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'agent_id'              => [
            'name'        => 'agent_id',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 40,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'bap_id'                => [
            'name'        => 'bap_id',
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
        'corps_id'              => [
            'name'        => 'corps_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'created_on'            => [
            'name'        => 'created_on',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => FALSE,
            'default'     => '(\'now\'::text)::timestamp(0) without time zone',
            'position'    => 25,
            'commentaire' => NULL,
        ],
        'd_debut'               => [
            'name'        => 'd_debut',
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
        'd_fin'                 => [
            'name'        => 'd_fin',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'deleted_on'            => [
            'name'        => 'deleted_on',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 27,
            'commentaire' => NULL,
        ],
        'grade_id'              => [
            'name'        => 'grade_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'histo_createur_id'     => [
            'name'        => 'histo_createur_id',
            'type'        => 'int',
            'bdd-type'    => 'bigint',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 28,
            'commentaire' => NULL,
        ],
        'histo_destructeur_id'  => [
            'name'        => 'histo_destructeur_id',
            'type'        => 'int',
            'bdd-type'    => 'bigint',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 30,
            'commentaire' => NULL,
        ],
        'histo_modificateur_id' => [
            'name'        => 'histo_modificateur_id',
            'type'        => 'int',
            'bdd-type'    => 'bigint',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 29,
            'commentaire' => NULL,
        ],
        'id'                    => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'bigint',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 8,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'id_orig'               => [
            'name'        => 'id_orig',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'source_id'             => [
            'name'        => 'source_id',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 128,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'structure_id'          => [
            'name'        => 'structure_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        't_administratif'       => [
            'name'        => 't_administratif',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 16,
            'commentaire' => NULL,
        ],
        't_cdd'                 => [
            'name'        => 't_cdd',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 13,
            'commentaire' => NULL,
        ],
        't_cdi'                 => [
            'name'        => 't_cdi',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 12,
            'commentaire' => NULL,
        ],
        't_chercheur'           => [
            'name'        => 't_chercheur',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 17,
            'commentaire' => NULL,
        ],
        't_conge_parental'      => [
            'name'        => 't_conge_parental',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 31,
            'commentaire' => NULL,
        ],
        't_detache_in'          => [
            'name'        => 't_detache_in',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 19,
            'commentaire' => NULL,
        ],
        't_detache_out'         => [
            'name'        => 't_detache_out',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 20,
            'commentaire' => NULL,
        ],
        't_dispo'               => [
            'name'        => 't_dispo',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 21,
            'commentaire' => NULL,
        ],
        't_doctorant'           => [
            'name'        => 't_doctorant',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 18,
            'commentaire' => NULL,
        ],
        't_emerite'             => [
            'name'        => 't_emerite',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 23,
            'commentaire' => NULL,
        ],
        't_enseignant'          => [
            'name'        => 't_enseignant',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 15,
            'commentaire' => NULL,
        ],
        't_heberge'             => [
            'name'        => 't_heberge',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 22,
            'commentaire' => NULL,
        ],
        't_longue_maladie'      => [
            'name'        => 't_longue_maladie',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 32,
            'commentaire' => NULL,
        ],
        't_retraite'            => [
            'name'        => 't_retraite',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 24,
            'commentaire' => NULL,
        ],
        't_titulaire'           => [
            'name'        => 't_titulaire',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 11,
            'commentaire' => NULL,
        ],
        't_vacataire'           => [
            'name'        => 't_vacataire',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '\'N\'',
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'updated_on'            => [
            'name'        => 'updated_on',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 26,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on