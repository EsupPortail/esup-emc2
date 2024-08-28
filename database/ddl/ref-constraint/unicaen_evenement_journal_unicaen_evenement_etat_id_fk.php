<?php

//@formatter:off

return [
    'schema'      => 'public',
    'name'        => 'unicaen_evenement_journal_unicaen_evenement_etat_id_fk',
    'table'       => 'unicaen_evenement_journal',
    'rtable'      => 'unicaen_evenement_etat',
    'update_rule' => 'NO ACTION',
    'delete_rule' => 'SET NULL',
    'index'       => 'pk_evenement_etat',
    'columns'     => [
        'etat_id' => 'id',
    ],
];

//@formatter:on
