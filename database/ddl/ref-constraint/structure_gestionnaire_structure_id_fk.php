<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'structure_gestionnaire_structure_id_fk',
    'table'       => 'structure_gestionnaire',
    'rtable'      => 'structure',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'structure_pk',
    'columns'     => [
        'structure_id' => 'id',
    ],
];

//@formatter:on
