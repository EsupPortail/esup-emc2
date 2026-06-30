<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'emploirepere_codefonction_metier_familleprofessionnelle_id_fk',
    'table'       => 'emploirepere_codefonction',
    'rtable'      => 'metier_familleprofessionnelle',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'metier_famille_pk',
    'columns'     => [
        'famille_professionnelle_id' => 'id',
    ],
];

//@formatter:on
