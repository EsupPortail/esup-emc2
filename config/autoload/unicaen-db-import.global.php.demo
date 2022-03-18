<?php

namespace Application;

use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'import' => [
        'connections' => [
            // Format: 'identifiant unique' => 'nom de la connexion Doctrine'
            'default' => 'doctrine.connection.orm_default',
            'octopus' => 'doctrine.connection.orm_octopus',
        ],

        'synchros' => [
            [
                'name' => 'Import_AGENT',
                'source' => [
                    'name'               => 'Agents geres par la DRH',
                    'select'             => 'SELECT * FROM v_agent',
                    'connection'         => 'octopus',
                    'source_code_column' => 'C_INDIVIDU',
                    'columns'            => [],
                ],
                'destination' => [
                    'name'               => 'Agents gérés par la DRH',
                    'table'              => 'agent',
                    'connection'         => 'default',
                    'source_code_column' => 'c_individu',
                    'columns'            => [],
                ],
            ],
            [
                'name' => 'Import_BAP',
                'source' => [
                    'name'               => 'BAP des agents remonté depuis OCTOPUS',
                    'select'             => 'SELECT id, code as c_bap, lib_court, lib_long, d_fermeture AS histo FROM correspondance',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['c_bap', 'lib_court', 'lib_long', 'histo'],
                ],
                'intermediate_table' => 'src_correspondance',
                'destination' => [
                    'name'               => 'BAP des agents remonté depuis OCTOPUS',
                    'table'              => 'carriere_correspondance',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['c_bap', 'lib_court', 'lib_long', 'histo'],
                ],
            ],
            [
                'name' => 'Import_CORPS',
                'source' => [
                    'name'               => 'CORPS des agents remonté depuis OCTOPUS',
                    'select'             => 'SELECT id, code, lib_court, lib_long, categorie_code as categorie, d_fermeture AS histo FROM corps',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['code', 'lib_court', 'lib_long', 'categorie', 'histo'],
                ],
                'intermediate_table' => 'src_corps',
                'destination' => [
                    'name'               => 'CORPS des agents remonté depuis OCTOPUS',
                    'table'              => 'carriere_corps',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['code', 'lib_court', 'lib_long', 'categorie', 'histo'],
                ],
            ],
            [
                'name' => 'Import_GRADE',
                'source' => [
                    'name'               => 'GRADE des agents remontés depuis OCTOPUS',
                    'select'             => 'SELECT id, code, lib_court, lib_long, d_fermeture AS histo FROM grade',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['code', 'lib_court', 'lib_long', 'histo'],
                ],
                'intermediate_table' => 'src_grade',
                'destination' => [
                    'name'               => 'GRADE des agents remontés depuis OCTOPUS',
                    'table'              => 'carriere_grade',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['code', 'lib_court', 'lib_long', 'histo'],
                ],
            ],
            [
                'name' => 'Import_STRUCTURE_TYPE',
                'source' => [
                    'name'               => 'Type des structures remonté depuis OCTOPUS',
                    'select'             => 'SELECT ID, CODE, LIBELLE FROM STRUCTURE_TYPE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['CODE', 'LIBELLE'],
                ],
                'intermediate_table' => 'src_structure_type',
                'destination' => [
                    'name'               => 'Type des structures remonté depuis OCTOPUS',
                    'table'              => 'structure_type',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['code', 'libelle'],
                ],
            ],
            [
                'name' => 'Import_STRUCTURE',
                'source' => [
                    'name'               => 'Agents geres par la DRH',
                    'select'             => 'SELECT ID, CODE, LIBELLE_COURT, LIBELLE_LONG, TYPE_ID, DATE_OUVERTURE AS OUVERTURE, DATE_FERMETURE AS FERMETURE, PARENT_ID, NIV2_ID FROM STRUCTURE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                ],
                'intermediate_table' => 'src_structure',
                'destination' => [
                    'name'               => 'Structure stockees dans octopus',
                    'table'              => 'structure',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['code', 'libelle_court', 'libelle_long', 'type_id', 'ouverture', 'fermeture', 'parent_id', 'niv2_id'],
                ],
            ],
            [
                'name' => 'Import_AGENT_QUOTITE',
                'source' => [
                    'name'               => 'Quotité travaillé par les agents',
                    'select'             => 'SELECT ID, INDIVIDU_ID AS AGENT_ID, DEBUT AS DEBUT, FIN AS FIN, QUOTITE FROM INDIVIDU_QUOTITE JOIN V_AGENT on V_AGENT.C_INDIVIDU = INDIVIDU_QUOTITE.INDIVIDU_ID',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                ],
                'intermediate_table' => 'src_agent_quotite',
                'destination' => [
                    'name'               => 'Grade des agents gérés par la DRH',
                    'table'              => 'agent_carriere_quotite',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['agent_id', 'debut', 'fin', 'quotite'],
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
