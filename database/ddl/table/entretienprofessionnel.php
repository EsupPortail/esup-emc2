<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'entretienprofessionnel',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => 'entretienprofessionnel_id_seq',
    'columns'     => [
        'acceptation'           => [
            'name'        => 'acceptation',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'agent'                 => [
            'name'        => 'agent',
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
        'campagne_id'           => [
            'name'        => 'campagne_id',
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
        'date_entretien'        => [
            'name'        => 'date_entretien',
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
        'formation_instance'    => [
            'name'        => 'formation_instance',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'formulaire_instance'   => [
            'name'        => 'formulaire_instance',
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
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'histo_creation'        => [
            'name'        => 'histo_creation',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'histo_destructeur_id'  => [
            'name'        => 'histo_destructeur_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'histo_destruction'     => [
            'name'        => 'histo_destruction',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'histo_modificateur_id' => [
            'name'        => 'histo_modificateur_id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'histo_modification'    => [
            'name'        => 'histo_modification',
            'type'        => 'date',
            'bdd-type'    => 'timestamp without time zone',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 6,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 13,
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
            'default'     => 'nextval(\'entretienprofessionnel_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'lieu'                  => [
            'name'        => 'lieu',
            'type'        => 'clob',
            'bdd-type'    => 'text',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'responsable_id'        => [
            'name'        => 'responsable_id',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 40,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'token'                 => [
            'name'        => 'token',
            'type'        => 'string',
            'bdd-type'    => 'character varying',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
