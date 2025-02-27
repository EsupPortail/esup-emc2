<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'formation_instance_inscrit_formation_instance_id_fk',
    'table'       => 'formation_instance_inscrit',
    'rtable'      => 'formation_instance',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'formation_instance_pk',
    'columns'     => [
        'instance_id' => 'id',
    ],
];

//@formatter:on
