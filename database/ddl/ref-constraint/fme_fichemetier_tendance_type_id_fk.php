<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'fme_fichemetier_tendance_type_id_fk',
    'table'       => 'fichemetier_tendance_element',
    'rtable'      => 'fichemetier_tendance_type',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'fichemetier_tendance_type_pk',
    'columns'     => [
        'tendancetype_id' => 'id',
    ],
];

//@formatter:on
