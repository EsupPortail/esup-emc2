<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'epo_unicaen_utilisateur_user_id_fk_3',
    'table'       => 'entretienprofessionnel_observateur',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'unicaen_utilisateur_user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
