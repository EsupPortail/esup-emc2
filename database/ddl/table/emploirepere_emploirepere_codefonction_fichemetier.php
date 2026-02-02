<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'emploirepere_emploirepere_codefonction_fichemetier',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'emploirepere_emploirepere_codefonction_fichemetier_id_seq',
    'columns'     => [
        'codefonction_id' => [
            'name'        => 'codefonction_id',
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
        'emploirepere_id' => [
            'name'        => 'emploirepere_id',
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
        'fichemetier_id'  => [
            'name'        => 'fichemetier_id',
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
        'id'              => [
            'name'        => 'id',
            'type'        => 'int',
            'bdd-type'    => 'integer',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 4,
            'nullable'    => FALSE,
            'default'     => 'nextval(\'emploirepere_emploirepere_codefonction_fichemetier_id_seq\'::regclass)',
            'position'    => 1,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
