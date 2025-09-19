<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'element_competence_referentiel_unicaen_utilisateur_user_id_fk3',
    'table'       => 'element_competence_referentiel',
    'rtable'      => 'unicaen_utilisateur_user',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'NO ACTION',
    'index'       => 'user_pkey',
    'columns'     => [
        'histo_destructeur_id' => 'id',
    ],
];

//@formatter:on
