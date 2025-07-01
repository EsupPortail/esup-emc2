<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'metier_domaine_famille_metier_familleprofessionnelle_id_fk',
    'table'       => 'metier_domaine_famille',
    'rtable'      => 'metier_familleprofessionnelle',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'metier_famille_id_uindex',
    'columns'     => [
        'famille_id' => 'id',
    ],
];

//@formatter:on
