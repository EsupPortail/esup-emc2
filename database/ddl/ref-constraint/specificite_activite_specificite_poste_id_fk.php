<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'specificite_activite_specificite_poste_id_fk',
    'table'       => 'ficheposte_activite_specifique',
    'rtable'      => 'ficheposte_specificite',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'specificite_poste_pk',
    'columns'     => [
        'specificite_id' => 'id',
    ],
];

//@formatter:on
