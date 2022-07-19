<?php

namespace Application;

use UnicaenPrivilege\Guard\PrivilegeController;

return [
    /**
     * Configuration globale du module UnicaenDbImport.
     */
    'import' => [
        'connections' => [
            'default' => 'doctrine.connection.orm_default',
            'octopus' => 'doctrine.connection.orm_octopus',
        ],

        'histo_columns_aliases' => [
            'created_on' => 'HISTO_CREATION',     // date/heure de création de l'enregistrement
            'updated_on' => 'HISTO_MODIFICATION', // date/heure de modification
            'deleted_on' => 'HISTO_DESTRUCTION',  // date/heure de suppression
            'created_by' => 'HISTO_CREATEUR_ID',     // auteur de la création de l'enregistrement
            'updated_by' => 'HISTO_MODIFICATEUR_ID', // auteur de la modification
            'deleted_by' => 'HISTO_DESTRUCTEUR_ID',  // auteur de la suppression
        ],

        'histo_columns_values' => [
            'created_by' => 1, // auteur de la création de l'enregistrement
            'updated_by' => 1, // auteur de la modification
            'deleted_by' => 1, // auteur de la suppression
        ],
        'use_import_observ' => false,

        /**
         * Liste des imports.
         */
        'synchros' => [
            [
                'name' => 'Import_AGENT_ECHELON',
                'source' => [
                    'name'               => 'octopus',
                    'select'             => '
                        SELECT IE.ID AS ID_ORIG, IE.INDIVIDU_ID AS AGENT_ID, IE.ECHELON AS ECHELON, IE.D_DEBUT AS DATE_PASSAGE 
                        FROM INDIVIDU_ECHELON IE 
                        JOIN V_PREECOG_AGENT on V_PREECOG_AGENT.C_INDIVIDU = IE.INDIVIDU_ID
                        ORDER BY IE.ID'
                    ,
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'intermediate_table' => 'src_agent_echelon',
                'destination' => [
                    'name'               => 'Echelon des agents gérés par la DRH',
                    'table'              => 'agent_carriere_echelon_2',
                    'connection'         => 'default',
                    'source_code_column' => 'id_orig',
                ],
            ],
        ],
    ],

    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'UnicaenDbImport\Controller\Console',
                    'action'     => [
                        'runImport',
                        'runSynchro',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

];
