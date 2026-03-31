<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_ficheposte_fiche_poste_id_fk',
    'table'       => 'structure_ficheposte',
    'rtable'      => 'ficheposte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fiche_metier_id_uindex',
    'columns'     => [
        'ficheposte_id' => 'id',
    ],
];

//@formatter:on
