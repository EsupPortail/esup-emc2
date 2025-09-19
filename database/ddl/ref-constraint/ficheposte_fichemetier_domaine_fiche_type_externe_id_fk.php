<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'ficheposte_fichemetier_domaine_fiche_type_externe_id_fk',
    'table'       => 'ficheposte_fichemetier_domaine',
    'rtable'      => 'ficheposte_fichemetier',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_type_externe_pk',
    'columns'     => [
        'fichemetierexterne_id' => 'id',
    ],
];

//@formatter:on
