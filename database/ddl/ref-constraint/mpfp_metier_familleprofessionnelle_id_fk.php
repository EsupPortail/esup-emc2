<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'mpfp_metier_familleprofessionnelle_id_fk',
    'table'       => 'missionprincipale_familleprofessionnelle',
    'rtable'      => 'metier_familleprofessionnelle',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'metier_famille_pk',
    'columns'     => [
        'famille_id' => 'id',
    ],
];

//@formatter:on
