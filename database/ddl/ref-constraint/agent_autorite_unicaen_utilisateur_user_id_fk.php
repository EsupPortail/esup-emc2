<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'agent_autorite_unicaen_utilisateur_user_id_fk',
    'table'       => 'agent_hierarchie_autorite',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_modificateur_id' => 'id',
    ],
];

//@formatter:on
