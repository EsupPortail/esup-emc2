<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epccr_unicaen_autoform_champ_id_fk',
    'table'       => 'entretienprofessionnel_campagne_configuration_recopie',
    'rtable'      => 'unicaen_autoform_champ',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'autoform_champ_pk',
    'columns'     => [
        'from_champ_id' => 'id',
    ],
];

//@formatter:on
