<?php

namespace Application;

use UnicaenPrivilege\Guard\PrivilegeController;

return [
    /**
     * Configuration globale du module UnicaenDbImport.
     */
    'import' => [
        //
        // Connexions.
        //
        'connections' => [
            //
            // Bases de données.
            // Format: 'identifiant unique' => 'nom de la connexion Doctrine'
            //
            'default' => 'doctrine.connection.orm_default',
            'octopus' => 'doctrine.connection.orm_octopus',
        ],
        /**
         * Liste des imports.
         */
        'synchros' => [
//            [
//                'name' => 'Import_AGENT',
                /**
                 * Configuration de la source de données à importer :
                 * - 'name'               : petit nom (unique) de la source
                 * - 'table'              : nom de la table source contenant les données à importer
                 * - 'select'             : select SQL de mise en forme des données source à importer (NB: antinomique avec 'table')
                 * - 'connection'         : identifiant de la connexion Doctrine à la base de données source
                 * - 'source_code_column' : nom de la colonne dans la table/vue source contenant l'identifiant unique
                 * - 'columns'            : liste ordonnée des noms des colonnes à prendre en compte dans la table/vue source
                 */
//                'source' => [
//                    'name'               => 'Agents geres par la DRH',
//                    'table'              => 'V_AGENT',
//                    'select'              => 'SELECT * FROM V_AGENT',
//                    'connection'         => 'octopus',
//                    'source_code_column' => 'C_INDIVIDU',
//                    'columns'            => ['C_SRC_INDIVIDU', 'C_SOURCE', 'PRENOM', 'NOM_USAGE'],
//                ],
                /**
                 * Forçage éventuel du nom de la table intermédiaire utilisée lorsque source et destination
                 * ne partagent pas la même connexion. NB: cette table intermédiaire est créée/peuplée/supprimée
                 * dans la base de données destination à chaque import.
                 * En l'absence de ce forçage, le nom de la table intermédiaire sera celui de la table destination
                 * préfixé par "src_".
                 */
//                'intermediate_table' => 'src_agent',
                /**
                 * Configuration de la destination des données importées :
                 * - 'name'               : petit nom (unique) de la destination
                 * - 'table'              : nom de la table destination vers laquelle les données sont importées
                 * - 'connection'         : identifiant de la connexion Doctrine à la base de données destination
                 * - 'source_code_column' : nom de la colonne dans la table destination contenant l'identifiant unique
                 * - 'columns'            : liste ordonnée des noms des colonnes importées dans la table destination
                 * - 'columns_to_char'    : format sprintf nécessaire pour mettre des colonnes au format requis (string)
                 */
//                'destination' => [
//                    'name'               => 'Agents gérés par la DRH',
//                    'table'              => 'agent',
//                    'connection'         => 'default',
//                    'source_code_column' => 'c_individu',
//                    'columns'            => ['c_src_individu', 'c_source', 'prenom', 'nom_usage'],
//                    'columns_to_char' => [
//                        'debut_validite' => "TO_CHAR(%s,'YYYY-MM-DD')", // car colonne destination de type TIMESTAMP
//                        'fin_validite'   => "TO_CHAR(%s,'YYYY-MM-DD')", // idem
//                    ],
//                ],
//            ],
            [
                'name' => 'Import_AGENT',
                'source' => [
                    'name'               => 'Agents geres par la DRH',
                    'select'             => 'SELECT * FROM V_PREECOG_AGENT',
                    'connection'         => 'octopus',
                    'source_code_column' => 'C_INDIVIDU',
                    'columns'            => ['PRENOM', 'NOM_USAGE'],
                ],
                'destination' => [
                    'name'               => 'Agents gérés par la DRH',
                    'table'              => 'agent',
                    'connection'         => 'default',
                    'source_code_column' => 'c_individu',
                    'columns'            => ['prenom', 'nom_usage', 'harp_id'],
                ],
            ],
            [
                'name' => 'Import_AFFECTATION',
                'source' => [
                    'name'               => 'Affectations des agents',
                    'select'             => 'SELECT * FROM V_PREECOG_AFFECTATION',
                    'connection'         => 'octopus',
                    'source_code_column' => 'AFFECTATION_ID',
                    'columns'            => ['INDIVIDU_ID', 'STRUCTURE_ID', 'DATE_DEBUT', 'DATE_FIN', 'ID_ORIG', 'T_PRINCIPALE'],
                ],
//                'intermediate_table' => 'src_agent',
                'destination' => [
                    'name'               => 'Affectation des agents gérés par la DRH',
                    'table'              => 'agent_affectation',
                    'connection'         => 'default',
                    'source_code_column' => 'affectation_id',
                    'columns'            => ['individu_id', 'structure_id', 'date_debut', 'date_fin', 'id_orig', 't_principale'],
                ],
            ],
            [
                'name' => 'Import_AGENT_GRADE',
                'source' => [
                    'name'               => 'Grades liés aux agents de PreeCog',
                    'select'             => 'SELECT ID, ID_ORIG, AGENT_ID, STRUCTURE_ID, CORPS_ID, GRADE_ID, BAP_ID, D_DEBUT, D_FIN FROM V_PREECOG_GRADE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    //'columns'            => ['AGENT_ID', 'STRUCTURE_ID', 'CORPS_ID', 'GRADE_ID', 'BAP_ID', 'DATE_DEBUT', 'DATE_FIN'],
                ],
                'intermediate_table' => 'src_agent_grade',
                'destination' => [
                    'name'               => 'Grade des agents gérés par la DRH',
                    'table'              => 'agent_grade',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['id_orig', 'agent_id', 'structure_id', 'corps_id', 'grade_id', 'bap_id', 'd_debut', 'd_fin'],
                ],
            ],
            [
                'name' => 'Import_AGENT_QUOTITE',
                'source' => [
                    'name'               => 'Quotité travaillé par les agents',
                    'select'             => 'SELECT ID, INDIVIDU_ID AS AGENT_ID, D_DEBUT AS DEBUT, D_FIN AS FIN, QUOTITE FROM INDIVIDU_QUOTITE JOIN V_PREECOG_AGENT on V_PREECOG_AGENT.C_INDIVIDU = INDIVIDU_QUOTITE.INDIVIDU_ID',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    //'columns'            => ['AGENT_ID', 'STRUCTURE_ID', 'CORPS_ID', 'GRADE_ID', 'BAP_ID', 'DATE_DEBUT', 'DATE_FIN'],
                ],
                'intermediate_table' => 'src_agent_quotite',
                'destination' => [
                    'name'               => 'Grade des agents gérés par la DRH',
                    'table'              => 'agent_quotite',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['agent_id', 'debut', 'fin', 'quotite'],
                ],
            ],
            [
                'name' => 'Import_STATUT',
                'source' => [
                    'name'               => 'Statut des agents geres par la DRH',
                    'select'             => 'SELECT ID, ID_ORIG, C_SOURCE, AGENT_ID, STRUCTURE_ID, D_DEBUT, D_FIN, T_TITULAIRE, T_CDI, T_CDD, T_VACATAIRE, T_ENSEIGNANT, T_ADMINISTRATIF, T_CHERCHEUR, T_ETUDIANT, T_AUDITEUR_LIBRE, T_DOCTORANT, T_DETACHE_IN, T_DETACHE_OUT, T_DISPO, T_HEBERGE, T_EMERITE, T_RETRAITE    FROM V_PREECOG_STATUT',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
//                    'columns'            => ['ID_ORIG', 'C_SOURCE', 'INDIVIDU_ID', 'STRUCTURE_ID', 'D_DEBUT', 'D_FIN', 'T_TITULAIRE', 'T_CDI', 'T_CDD', 'T_VACATAIRE', 'T_ENSEIGNANT', 'T_ADMINISTRATIF', 'T_CHERCHEUR', 'T_ETUDIANT', 'T_AUDITEUR_LIBRE', 'T_DOCTORANT', 'T_DETACHE_IN', 'T_DETACHE_OUT', 'T_DISPO', 'T_HEBERGE', 'T_EMERITE', 'T_RETRAITE', 'T_CLD', 'T_CLM'],
                ],
                'intermediate_table' => 'src_agent_statut',
                'destination' => [
                    'name'               => 'Statut des agents geres par la DRH',
                    'table'              => 'agent_statut',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['id_orig', 'c_source', 'individu_id', 'structure_id', 'd_debut', 'd_fin', 't_titulaire', 't_cdi', 't_cdd', 't_vacataire', 't_enseignant', 't_administratif', 't_chercheur', 't_etudiant', 't_auditeur_libre', 't_doctorant', 't_detache_in', 't_detache_out', 't_dispo', 't_heberge', 't_emerite', 't_retraite', 't_cld', 't_clm'],
                    'columns_to_char' => [
                        'd_debut' => "TO_CHAR(%s,'YYYY-MM-DD')",
                        'd_fin'   => "TO_CHAR(%s,'YYYY-MM-DD')",
                    ],
                ],
            ],
            [
                'name' => 'Import_CORPS',
                'source' => [
                    'name'               => 'Corps des agents remontés depuis OCTOPUS',
                    'select'             => 'SELECT ID,  C_CORPS as CODE, C_CATEGORIE as CATEGORIE, LIB_COURT, LIB_LONG, HISTO_DESTRUCTION as HISTO FROM CORPS',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                ],
                'intermediate_table' => 'src_corps',
                'destination' => [
                    'name'               => 'Corps des agents remonté depuis OCTOPUS',
                    'table'              => 'corps',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['code', 'categorie', 'lib_court', 'lib_long', 'histo'],
                ],
            ],
                        [
                'name' => 'Import_GRADE',
                'source' => [
                    'name'               => 'Grades des agents remonté depuis OCTOPUS',
                    'select'             => 'SELECT ID, C_GRADE AS CODE, LIB_COURT, LIB_LONG, HISTO_DESTRUCTION AS HISTO FROM GRADE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['LIB_COURT', 'LIB_LONG', 'CODE', 'HISTO'],
                ],
                'intermediate_table' => 'src_grade',
                'destination' => [
                    'name'               => 'Grades des agents remonté depuis OCTOPUS',
                    'table'              => 'grade',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['lib_court', 'lib_long', 'code', 'histo'],
                ],
            ],
            [
                'name' => 'Import_BAP',
                'source' => [
                    'name'               => 'BAP des agents remonté depuis OCTOPUS',
                    'select'             => 'SELECT ID, C_BAP, LIB_COURT, LIB_LONG, HISTO_DESTRUCTION AS HISTO FROM BAP',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['C_BAP', 'LIB_COURT', 'LIB_LONG', 'HISTO'],
                ],
                'intermediate_table' => 'src_correspondance',
                'destination' => [
                    'name'               => 'BAP des agents remonté depuis OCTOPUS',
                    'table'              => 'correspondance',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['c_bap', 'lib_court', 'lib_long', 'histo'],
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
                    'select'             => 'SELECT ID, CODE, LIBELLE_COURT, LIBELLE_LONG, TYPE_ID, DATE_OUVERTURE AS OUVERTURE, DATE_FERMETURE AS FERMETURE, HISTO, PARENT_ID FROM V_PREECOG_STRUCTURE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                ],
                'intermediate_table' => 'src_structure',
                'destination' => [
                    'name'               => 'Structure stockees dans octopus',
                    'table'              => 'structure',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['code', 'libelle_court', 'libelle_long', 'type_id', 'ouverture', 'fermeture', 'histo','parent_id'],
                ],
            ],
            [
                'name' => 'Import_RESPONSABLE',
                'source' => [
                    'name'               => 'Responsables des structures',
                    'select'             => 'SELECT * FROM V_PREECOG_RESPONSABLE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['STRUCTURE_ID', 'AGENT_ID', 'FONCTION_ID', 'DATE_DEBUT', 'DATE_FIN'],
                ],
                'destination' => [
                    'name'               => 'Responsables des strucures',
                    'table'              => 'structure_responsable',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['structure_id', 'agent_id', 'fonction_id', 'date_debut', 'date_fin'],
                ],
            ],
//            [
//                'name' => 'Import_FONCTION',
//                'source' => [
//                    'name'               => 'Fonctions remontées de PrEECoG',
//                    'select'             => 'SELECT * FROM V_PREECOG_FONCTION',
//                    'connection'         => 'octopus',
//                    'source_code_column' => 'ID',
//                    'columns'            => ['PARENT_ID', 'CODE', 'NIVEAU'],
//                ],
//                'intermediate_table' => 'src_fonction',
//                'destination' => [
//                    'name'               => 'Fonctions remontées de OCTOPUS',
//                    'table'              => 'fonction',
//                    'connection'         => 'default',
//                    'source_code_column' => 'id',
//                    'columns'            => ['parent_id', 'code', 'niveau'],
//                ],
//            ],
//            [
//                'name' => 'Import_FONCTION_LIBELLE',
//                'source' => [
//                    'name'               => 'Libellés des fonctions remontées de PrEECoG',
//                    'select'             => 'SELECT * FROM V_PREECOG_FONCTION_LIBELLE',
//                    'connection'         => 'octopus',
//                    'source_code_column' => 'ID',
//                    'columns'            => ['FONCTION_ID', 'LIBELLE', 'GENRE', 'DEFAUT'],
//                ],
//                'intermediate_table' => 'src_fonction_libelle',
//                'destination' => [
//                    'name'               => 'Libellés des fonctions remontées de OCTOPUS',
//                    'table'              => 'fonction_libelle',
//                    'connection'         => 'default',
//                    'source_code_column' => 'id',
//                    'columns'            => ['fonction_id', 'libelle', 'genre', 'defaut'],
//                ],
//            ],
//

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
