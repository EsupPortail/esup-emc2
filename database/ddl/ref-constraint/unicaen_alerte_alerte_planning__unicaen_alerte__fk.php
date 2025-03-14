<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_alerte_alerte_planning__unicaen_alerte__fk',
    'table'       => 'unicaen_alerte_alerte_planning',
    'rtable'      => 'unicaen_alerte_alerte',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_alerte_alerte__pk',
    'columns'     => [
        'alerte_id' => 'id',
    ],
];

//@formatter:on
