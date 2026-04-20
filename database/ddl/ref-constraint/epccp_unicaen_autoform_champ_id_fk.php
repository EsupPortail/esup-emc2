<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epccp_unicaen_autoform_champ_id_fk',
    'table'       => 'entretienprofessionnel_campagne_configuration_presaisie',
    'rtable'      => 'unicaen_autoform_champ',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'autoform_champ_pk',
    'columns'     => [
        'champ_id' => 'id',
    ],
];

//@formatter:on
