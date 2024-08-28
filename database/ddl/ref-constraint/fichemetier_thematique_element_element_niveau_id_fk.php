<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fichemetier_thematique_element_element_niveau_id_fk',
    'table'       => 'fichemetier_thematique_element',
    'rtable'      => 'element_niveau',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'maitrise_niveau_pk',
    'columns'     => [
        'niveau_id' => 'id',
    ],
];

//@formatter:on
