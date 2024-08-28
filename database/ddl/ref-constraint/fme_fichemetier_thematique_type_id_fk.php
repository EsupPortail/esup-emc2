<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fme_fichemetier_thematique_type_id_fk',
    'table'       => 'fichemetier_thematique_element',
    'rtable'      => 'fichemetier_thematique_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fichemetier_thematique_type_pk',
    'columns'     => [
        'thematiquetype_id' => 'id',
    ],
];

//@formatter:on
