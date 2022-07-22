<?php

namespace Application;

use UnicaenDbImport\Privilege\ImportPrivilege;
use UnicaenDbImport\Privilege\LogPrivilege;
use UnicaenDbImport\Privilege\ObservationPrivilege;
use UnicaenDbImport\Privilege\SynchroPrivilege;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'unicaen-db-import' => [
                        'label' => "Import/Synchro",
                        'route' => 'unicaen-db-import/import',
                        'resource' => ImportPrivilege::getResourceId(ImportPrivilege::LISTER),
                        'order' => 100,
                        'visible' => false,
                    ],
                    'administration' => [
                        'pages' => [
                            'import_synchro' => [
                                'label' => "Import/Synchro",
                                'route' => 'unicaen-db-import/import',
                                'resource' => ImportPrivilege::getResourceId(ImportPrivilege::LISTER),
                                'order' => 90000,
                                'dropdown-header' => true,
                            ],
                            'import' => [
                                'label' => "Imports",
                                'route' => 'unicaen-db-import/import',
                                'resource' => ImportPrivilege::getResourceId(ImportPrivilege::LISTER),
                                'order' => 90010,
                                'icon' => 'fas fa-angle-right',
                            ],
                            'synchro' => [
                                'label' => "Synchros",
                                'route' => 'unicaen-db-import/synchro',
                                'resource' => SynchroPrivilege::getResourceId(SynchroPrivilege::LISTER),
                                'order' => 90020,
                                'icon' => 'fas fa-angle-right',
                            ],
                            'log' => [
                                'label' => "Logs",
                                'route' => 'unicaen-db-import/log',
                                'resource' => LogPrivilege::getResourceId(LogPrivilege::LISTER),
                                'order' => 90030,
                                'icon' => 'fas fa-angle-right',
                            ],
                            'observ' => [
                                'label' => "Observations",
                                'route' => 'unicaen-db-import/observ',
                                'resource' => ObservationPrivilege::getResourceId(ObservationPrivilege::LISTER),
                                'order' => 90040,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    /**
     * Configuration globale du module UnicaenDbImport.
     */
    'import' => [
        'connections' => [
            'default' => 'doctrine.connection.orm_default',
            'octopus' => 'doctrine.connection.orm_octopus',
        ],

        'histo_columns_aliases' => [
//            'created_on' => 'HISTO_CREATION',     // date/heure de création de l'enregistrement
//            'updated_on' => 'HISTO_MODIFICATION', // date/heure de modification
//            'deleted_on' => 'HISTO_DESTRUCTION',  // date/heure de suppression
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

        'imports' => [],
        'synchros' => [
            [
                'name' => 'Import_AGENT_ECHELON',
                'order' => 140,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'DUMMY',
                    'select'             => "
                        SELECT IE.ID AS ID_ORIG, IE.INDIVIDU_ID AS AGENT_ID, IE.ECHELON AS ECHELON, IE.DATE_PASSAGE AS DATE_PASSAGE
                        FROM INDIVIDU_ECHELON IE
                        JOIN V_AGENT on V_AGENT.C_INDIVIDU = IE.INDIVIDU_ID
                    ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Echelon des agents gérés par la DRH',
                    'table' => 'agent_carriere_echelon',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => false,
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
