<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance_frais_formation_instance_inscrit_id_fk',
    'table'       => 'formation_instance_frais',
    'rtable'      => 'formation_instance_inscrit',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_instance_inscrit_pk',
    'columns'     => [
        'inscrit_id' => 'id',
    ],
];

//@formatter:on
