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
                'name' => 'CARRIERE_CORRESPONDANCE',
                'order' => 1000,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                        SELECT B.ID AS ID_ORIG, B.C_BAP, B.LIB_COURT, B.LIB_LONG, 
                               B.D_OUVERTURE,  B.D_FERMETURE 
                        FROM BAP B 
                        WHERE HISTO_DESTRUCTION IS NULL
                    ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des Correspondances (BAP, ...)',
                    'table' => 'carriere_correspondance',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'CARRIERE_CORPS',
                'order' => 1100,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT C.ID AS ID_ORIG, C.LIB_COURT, C.LIB_LONG, 
                                   C.C_CORPS AS CODE, C.C_CATEGORIE AS CATEGORIE,
                                   C.D_OUVERTURE,  C.D_FERMETURE 
                            FROM CORPS C 
                            WHERE HISTO_DESTRUCTION IS NULL
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des Corps',
                    'table' => 'carriere_corps',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'CARRIERE_GRADE',
                'order' => 1200,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT G.ID AS ID_ORIG, G.LIB_COURT, G.LIB_LONG, 
                                   G.C_GRADE AS CODE,
                                   G.D_OUVERTURE,  G.D_FERMETURE 
                            FROM GRADE G 
                            WHERE HISTO_DESTRUCTION IS NULL
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des Grades',
                    'table' => 'carriere_grade',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'STRUCTURE_TYPE',
                'order' => 2000,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT ST.ID AS ID_ORIG, ST.LIBELLE, ST.DESCRIPTION,  
                                   ST.CODE AS CODE
                            FROM STRUCTURE_TYPE ST 
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des types de structure',
                    'table' => 'structure_type',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'STRUCTURE',
                'order' => 2100,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT S.ID AS ID_ORIG, S.LIBELLE_COURT, S.LIBELLE_LONG, S.TYPE_ID AS TYPE_ID,
                                   S.CODE AS CODE, S.SIGLE AS SIGLE, S.EMAIL_GENERIQUE AS ADRESSE_FONCTIONNELLE,
                                   S.DATE_OUVERTURE AS D_OUVERTURE, S.DATE_FERMETURE AS D_FERMETURE,    
                                   S.PARENT_ID AS PARENT_ID, S.NIV2_ID AS NIV2_ID
                            FROM V_STRUCTURE S
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des structures',
                    'table' => 'structure',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'AGENT',
                'order' => 3000,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT A.C_INDIVIDU AS ID_ORIG, A.*
                            FROM V_PREECOG_AGENT A
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des agents',
                    'table' => 'agent',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'AGENT_AFFECTATION',
                'order' => 3100,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT IAE.AFFECTATION_ID AS ID_ORIG, IAE.AGENT_ID, IAE.STRUCTURE_ID, 
                                   IAE.T_PRINCIPALE, IAE.DATE_DEBUT, IAE.DATE_FIN 
                            FROM V_PREECOG_AFFECTATION  IAE
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des affectations des agents',
                    'table' => 'agent_carriere_affectation',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'AGENT_ECHELON',
                'order' => 3200,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT IE.ID AS ID_ORIG, IE.INDIVIDU_ID AS AGENT_ID, IE.ECHELON, 
                                   IE.D_DEBUT, IE.D_FIN 
                            FROM INDIVIDU_ECHELON IE
                            JOIN V_PREECOG_AGENT A ON A.C_INDIVIDU = IE.INDIVIDU_ID
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des affectations des agents',
                    'table' => 'agent_carriere_echelon',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'AGENT_GRADE',
                'order' => 3300,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT IAG.ID AS ID_ORIG, IAG.AGENT_ID, IAG.STRUCTURE_ID, 
                                   IAG.CORPS_ID, IAG.GRADE_ID, IAG.BAP_ID, 
                                   IAG.D_DEBUT, IAG.D_FIN 
                            FROM V_PREECOG_GRADE IAG
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des grades des agents',
                    'table' => 'agent_carriere_grade',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'AGENT_QUOTITE',
                'order' => 3400,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT IQ.ID AS ID_ORIG, IQ.INDIVIDU_ID AS AGENT_ID, IQ.QUOTITE, 
                                   IQ.D_DEBUT, IQ.D_FIN 
                            FROM INDIVIDU_QUOTITE IQ
                            JOIN V_PREECOG_AGENT A ON A.C_INDIVIDU = IQ.INDIVIDU_ID
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des quotités des agents',
                    'table' => 'agent_carriere_quotite',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'AGENT_STATUT',
                'order' => 3500,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT IAS.ID AS ID_ORIG, IAS.AGENT_ID, IAS.STRUCTURE_ID,
                                   IAS.D_DEBUT, IAS.D_FIN,
                                   IAS.T_TITULAIRE, IAS.T_CDI, IAS.T_CDD,
                                   IAS.T_ADMINISTRATIF, IAS.T_ENSEIGNANT, IAS.T_CHERCHEUR, IAS.T_VACATAIRE, IAS.T_DOCTORANT,
                                   IAS.T_DETACHE_IN, IAS.T_DETACHE_OUT, IAS.T_HEBERGE, IAS.T_DISPO, IAS.T_EMERITE, IAS.T_RETRAITE
                            FROM V_PREECOG_STATUT IAS
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des statuts des agents',
                    'table' => 'agent_carriere_statut',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'STRUCTURE_RESPONSABLE',
                'order' => 4100,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT PR.ID AS ID_ORIG, PR.AGENT_ID, PR.STRUCTURE_ID, PR.FONCTION_ID, 
                                   PR.DATE_DEBUT, PR.DATE_FIN 
                            FROM V_PREECOG_RESPONSABLE PR
                            JOIN V_PREECOG_AGENT A ON A.C_INDIVIDU = PR.AGENT_ID
                            JOIN V_STRUCTURE S ON S.ID = PR.STRUCTURE_ID
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des responsables de structures',
                    'table' => 'structure_responsable',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
                ],
            ],
            [
                'name' => 'STRUCTURE_GESTIONNAIRE',
                'order' => 4200,
                'source' => [
                    'name' => 'octopus',
                    'code' => 'OCTOPUS',
                    'select'             => "
                            SELECT PG.ID AS ID_ORIG, PG.AGENT_ID, PG.STRUCTURE_ID, PG.FONCTION_ID, 
                                   PG.DATE_DEBUT, PG.DATE_FIN 
                            FROM V_PREECOG_GESTIONNAIRE PG
                            JOIN V_PREECOG_AGENT A ON A.C_INDIVIDU = PG.AGENT_ID
                        ",
                    'connection' => 'octopus',
                    'source_code_column' => 'ID_ORIG',
                ],
                'destination' => [
                    'name' => 'Listing des gestionnaires de structures',
                    'table' => 'structure_gestionnaire',
                    'connection' => 'default',
                    'source_code_column' => 'id_orig',
                    'intermediate_table_auto_drop' => true,
                    'preserved_id' => true,
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
