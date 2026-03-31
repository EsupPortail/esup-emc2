<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'autoform_reponse_champ_fk',
    'table'       => 'unicaen_autoform_formulaire_reponse',
    'rtable'      => 'unicaen_autoform_champ',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'CASCADE',
    'index'       => 'autoform_champ_id_uindex',
    'columns'     => [
        'champ' => 'id',
    ],
];

//@formatter:on
